<?php

return [
    'create'                 => [
        'status'             => [
            'pending'        => '承認待ち',
            'published'      => '掲載',
            'limited'        => '掲載(限定)',
            'unpublished'    => '非掲載',
            'notpublish'    => '掲載終了'
        ],
        'button'             => [
            'customer'       => [
                'remove'     => '削除',
                'append'     => '条件を追加する'
            ],
            'upload'         => [
                'select'     => 'アップロード',
                'remove'     => '削除'
            ],
            'create'         => '作成する',
        ],
        'label'              => [
            'status'         => '状態',
            'disclosure'     => '公開範囲',
            'customer'       => [
                'homeMaker'  => 'ハウスメーカの顧客',
                'realEstate' => '不動産の顧客'
            ],
            'select'         => [
                'company'    => '法人',
                'user'       => '担当',
                'customer'   => '顧客'
            ],
            'condition'      => '建築条件',
            'location'       => '所在地',
            'price'          => '価格',
            'area'           => '土地面積',
            'contact'        => 'お問い合わせ先',
            'photo'          => '写真',
            'flyer'          => 'チラシ'
        ]
    ]
];