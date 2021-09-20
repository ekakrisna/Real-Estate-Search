# Laravel 6 Starter Kit

### Getting Started

Clone the repository from [here](https://bitbucket.org/gruneasia/laravel6-starter-kit/src/dev_main/).

**Requirements:**

-   laravel/framework: "^6.2"
-   PHP: "^7.2"
-   yajra/laravel-datatables-oracle: "~9.0"

**Development requirements:**

-   laravel/dusk: "5.8"

For more complete requirements, refer to laravel6-starter-kit/composer.json. It is recommended to run using Docker to ensure cross-compatibility.

##### Environment setup using Docker

-   **Docker ToolBox (for Windows Home)** - any windows other than Professional version.
    _2020/02/05 on Windows-10/DockerToolbox._

1.  Virtual machine settings & Start container.
    1-1. Settings -> Network -> Advanced -> Port fowarding.  
    Add the following settings:

    - http 80:80
    - mysql 3036:3036
    - phpmyadmin 8087:8087

    1-2. Settings -> Share Folders.
    Add project root path. (for naming convention please follow [here](https://docs.google.com/presentation/d/1pr75mvI1F40HeJAco7huWtuvTVkTXuYVX8_9hsqhorE/edit#slide=id.g5791ad7b9d_2_110))

2.  Building Docker containers.
    2-1. Execute "Docker Quickstart Terminal" shortcut. (_Created by Docker Toolbox._)
    2-2. Move to the directory where docker-compose.yml exists.
    2-3. Create and start container from Docker Quickstart Terminal
    `$ docker-compose up -d --build `
3.  Log in to container
    `$ docker exec -it --user=dev_user real_estate_search_php-fpm bash`

4.  Setup laravel environment.
    4-1. `$ cp .env.example .env`
    4-2. Modify .env file as follows.

        APP_URL=http://nginx

    DB_HOST=mysql  
    DB_DATABASE=db_real_estate_search  
    DB_USERNAME=dev_user  
    DB_PASSWORD=dev_pass

    4-3. `$ composer install`
    4-4. `$ php artisan key:generate`

5.  Run migration and seed.
    `$ php artisan migrate:fresh --seed`
6.  Access to http://127.0.0.1:80 or [localhost](localhost).
    Mail : admin@admin.com
    Pass : 12345678
7.  Access to phpMyAdmin is http://127.0.0.1:8087
8.  In case the seeded table has not appeared in phpMyAdmin, rebuild the docker image.
    `$ exit` to exit from the container.
    `$ docker-compose down`
    `$ docker-compose up -d --build`

-   **Docker Desktop (_for macOS system_)**.
    Follow the step for Docker Toolbox from 2-2.

##### Routing

In the current laravel starter kit, most routing are defined using laravel's resource controller. However, as most data are shown using yajrabox datatables tool (instead of showing single data on single page), the show controller is not used. Instead, the show controller is used to create AJAX request to retrieve json data for datatables.

##### Components

The starter template for the backend views are provided within _laravel6-starter-kit/resources/views/backend_ folder. All views extend _`/_base/app.blade.php`_, which includes _`/_base/nav_left.blade.php`_ in default. The contents are wrapped within `@yield('content-wrapper')` where two initial layout has been provided, they are _`/_base/content_datatables.blade.php`_ to show tables using yajrabox datatable and _`/_base/form.blade.php`_ to show form page.

Upon creating a new view page, we can readily utilize the provided template. Depending on the page type (form or index), we need to define the following things.

```php
@extends('backend._base.content_datatables')
```

, or

```php
@extends('backend._base.content_form')
```

depending on the page type. Then,

```php
@section('breadcrumbs')
    // breadcrumbs section goes here
@endsection

@section('top-buttons')
    // top button section goes here
@endsection
```

More importantly is the content section.

```php
@section('content')
    // content section goes here
@endsection
```

Form page retrieves the html elements using the provided internal components, while index page retrieves the table using yajrabox datatables tool.

**Internal components**
Several initial components are provided under _`/_components`_ directory. They can be readily used to build up form group.

To use these components, start with adding the following code.

```php
@component('backend._components.form_container', ["action" => $form_action, "page_type" => $page_type, "files" => false])

    //individual input component goes here
    //example
    @component('backend._components.input_text', ['name' => 'display_name', 'label' => __('label.name'), 'required' => 1, 'value' => $item->display_name]) @endcomponent
    @component('backend._components.input_buttons', ['page_type' => $page_type])@endcomponent

@endcomponent
```

where the second argument in `@component` is an array to pass the data into the component. The available keys for _`/_components/form_container.blade.php`_ are, `"id"`, "`action"`, `"files"`, `"method"`, and `"page_type"`.

More complete explanation is given by [Blade components and slots](https://laravel.com/docs/6.x/blade#components-and-slots).

**Yajrabox Datatables**
[Yajrabox Datatables](https://yajrabox.com/docs/laravel-datatables/master) provides a good way to manipulate table within laravel. In this laravel starter kit, the datatables uses the show controller to make an AJAX request and return json data to be used by datatables. The AJAX request is called from _`/content_datatables.blade.php`_ which includes the following javascript code for datatables setup..

```javascript
let table = $('#datatable').DataTable({
    //
    "ajax": "{{ url()->current() . "/json" }}",
    //
});
```

It then passed to `show` controller as follows.

```php
public function show( $param ){
    if( $param == 'json' ){

        $model = admin::where('admin_role_id', AdminRole::ROLE_GENERAL_ADMIN);
        return DatatablesHelper::json($model);

    }
    abort(404);
}
```

Finally, it calls `/app/Helpers/DatatablesHelper.php` where its main functionality is to retrieve data from database and add action columns to enable edit and delete data.

To use this datatable, all we need to do is to define our content section such as example below.

```php
@section('content')
    <th data-col="id">ID</th>
    <th data-col="display_name">@lang('label.name')</th>
    <th data-col="email">@lang('label.email')</th>
    <th data-col="action">@lang('label.action')</th>
@endsection
```

where `data-col` attribute is filled with the name of the column we want to retrieve from our database.

# history

-   13 feb 2020 described by Hilmi

---

old document

##Installation

-   cd docker/docker-compose/docker-desktop
-   Create and start container: `docker-compose up -d --build`
-   Log in to container: `docker exec -it --user=dev_user real_estate_search_php-fpm bash`
-   Create env fie: `cp .env.example .env`
-   Run composer install: `composer install`
-   Create application key: `php artisan key:generate`
-   Run migration and seed: php artisan migrate:fresh --seed
-   Access app to http://127.0.0.1:8086
-   Access to phpMyAdmin is http://127.0.0.1:8087

##Login to app
http://127.0.0.1:8086/admin/login

Super Admin `admin@admin` `12345678`

Company Admin `company@admin` `12345678`

Company User `user@company` `12345678`
