<?php

return [
    // grune config
    'grune_api_key'       => env('CHATWORK_API_KEY', null),
    'message_site-layout-is-changed'  =>"正常にスクレイピングの取得が行えませんでした。公開元のサイトのレイアウトが変更になった可能性があります。",
    'message_site-dataformat-is-changed'  =>"正常にスクレイピングデータの保存が出来ませんでした。公開元のサイトのデータ形式が変わった可能性があります。例 : 5,000万円 →　5000万円",
    // 'grune_room_id'       => env('GRUNE_ROOM_ID', null),
    // 'grune_to_account_id' => env('GRUNE_TO_ACCOUNT_ID', null),
];