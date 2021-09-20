<?php

namespace App\Helpers;

use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

class DatatablesHelper
{
    /**
     * @param object $eloquent
     * @param bool $edit - is edit button will included
     * @param bool $delete - is delete button will included
     * @param string $custom - custom HTML <a> Buttons
     *
     * @return string json - pagination, search etc. processed by Yajra Datatable package
     */
    public function json($eloquent, $btn_edit = true, $btn_delete = true, $btn_view = null, $btn_nested = null, $nested_parent_id = null)
    {
        return $this->instance($eloquent, $btn_edit, $btn_delete, $btn_view, $btn_nested, $nested_parent_id)->toJson();
    }

    public function instance($eloquent, $btn_edit = true, $btn_delete = true, $btn_view = null, $btn_nested = null, $nested_parent_id = null)
    {

        $datatablse = Datatables::of($eloquent);
        if (!empty($btn_edit) || !empty($btn_delete) || !empty($btn_view)) {
            $datatablse->addColumn('action', function ($query) use ($btn_edit, $btn_delete, $btn_view, $btn_nested, $nested_parent_id) {
                // *** Generate Action column ***

                // Generate link URLs
                $routeCurrent   = Route::currentRouteName();
                if (strpos($routeCurrent, '.datatable_json_post')) {
                    $routeShow     = str_replace('datatable_json_post', 'show', $routeCurrent);
                    $routeEdit      = str_replace('datatable_json_post', 'edit', $routeCurrent);
                    $routeDestroy   = str_replace('datatable_json_post', 'destroy', $routeCurrent);
                } else if(strpos($routeCurrent, '.datatable_json_get')){
                    $routeShow     = str_replace('datatable_json_get', 'show', $routeCurrent);
                    $routeEdit      = str_replace('datatable_json_get', 'edit', $routeCurrent);
                    $routeDestroy   = str_replace('datatable_json_get', 'destroy', $routeCurrent);
                } else {
                    $routeShow = str_replace('show', 'show', $routeCurrent);
                    $routeEdit = str_replace('show', 'edit', $routeCurrent);
                    $routeDestroy = str_replace('show', 'destroy', $routeCurrent);
                }

                if (!empty($nested_parent_id)) {
                    $url_edit   = URL::route($routeEdit, [$nested_parent_id, $query->id]);
                    $url_delete = URL::route($routeDestroy, [$nested_parent_id, $query->id]);
                } else {
                    $url_edit   = !empty($btn_edit) ? URL::route($routeEdit, $query->id) : '';
                    $url_delete = !empty($btn_delete) ? URL::route($routeDestroy, $query->id) : '';
                    $url_view   = !empty($btn_view) ? URL::route($routeShow, $query->id) : '';
                }

                // Generate button elements of HTML.
                $buttons = [];
                if (!empty($btn_view)) {
                    $buttons[] = '<a title="View Data" href="' . $url_view . '" class="btn btn-success"><i class="fas fa-eye"></i></a>';
                }
                if (!empty($btn_edit)) {
                    $buttons[] = '<a title="Edit Data" href="' . $url_edit . '" class="btn btn-info"><i class="fas fa-edit"></i></a>';
                }
                if (!empty($btn_nested)) {
                    $buttons[] = '<a href="' . URL::route($routeShow, $query->id) . '/' . $btn_nested['current_nest'] . '" class="btn btn-' . $btn_nested['style'] . '"><i class="fas fa-' . $btn_nested['icon'] . '"></i></a>';
                }
                if (!empty($btn_delete)) {
                    $buttons[] = '<a title="Delete Data" href="" data-remote="' . $url_delete . '" class="btn btn-danger deleteData"><i class="fas fa-trash"></i></a>';
                }

                $render = "";
                if (count($buttons) > 0) {
                    $render .= '<div class="btn-group-normal">';
                    foreach ($buttons as $button) {
                        $render .= $button;
                    }
                    $render .= '</div>';
                }
                return $render;
            });
        };

        return $datatablse->editColumn('id', '{{$id}}');
    }
}
