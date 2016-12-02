<?php

Route::group(['middleware' => ['sid', 'auth:admin', 'admin', 'csrf'], 'namespace' => 'Admin', 'domain' => 'admin.' . config('app.domain')], function() {
    Route::get("/", function() {
        return redirect('/users');
    });

    Route::get('/not', function() {
        foreach (\App\User::where('status', true)->get() as $user) {
            $not = new \App\Notification();
            $not->name = "Обновление дизайна пользовательской части";
            $not->text = "Рады сообщить вам об обновлении дизайна пользовательской части. Обновился внешний вид списка категорий и публикаций, изменился стандартный размер миниатюр, появилась возможность добавлять атрибут к публикации, который будет выглядеть как перекрывающая плашка на миниатюре и остальные мелочи. Если у вас возникнут трудности с сервисом или вы нашли баги — вы всегда можете обратиться в нашу службу поддержки, написав нам письмо: <a href='mailto:support@abckabinet.ru'>support@abckabinet.ru</a>";
            $not->user()->associate($user);
            $not->save();
        }
    });

    Route::get("/users", "UsersController@index");
    Route::get("/users/sort/{order_by}/{order}", "UsersController@sort");
    Route::get('/users/add', 'UsersController@add');
    Route::post('/users/store', 'UsersController@store');
    Route::get('/users/{user_id}/view', 'UsersController@view');
    Route::get('/users/{user_id}/edit', 'UsersController@edit');
    Route::get('/users/{user_id}/data', 'UsersController@data');
    Route::post('/users/{user_id}/update', 'UsersController@update');
    Route::get('/users/{user_id}/delete', 'UsersController@delete');
    Route::post('/users/batch', 'UsersController@batch');
    Route::post('/users/filters', 'UsersController@filters');
    Route::get('/users/filters/reset', 'UsersController@filtersReset');
    Route::get('/users/search/', 'UsersController@search');

    Route::get('excel', function() {
        \Excel::create('ABC Users', function($excel) {
            $excel->sheet('ABC Users', function($sheet) {
                $sheet->row(1, ["Имя", "Email", "Телефон", "Тариф", "Статус", "Дата добавления", "Истекает"]);
                $count = 2;
                $sheet->setOrientation('landscape');
                foreach (\App\User::orderBy('created_at', 'desc')->get() as $user) {
                    $sheet->row($count, [$user->name, $user->email, $user->phone, $user->plan->name, $user->status, $user->created_at, getDatetime($user->expires)]);
                    $count++;
                }
            });
        })->export('xls');
    });

    Route::get('/perpage/{perpage}', 'AccountController@perpage');
    Route::get("/logout", "AuthController@logout");

    Route::get('/copyk17', function() {
        $k17users = \App\Project::findOrFail("k17")->susers;

        $tmp_level = \App\Level::findOrFail(10174);
        $tmp_project = \App\Project::findOrFail("intensiv2016");

        foreach ($k17users as $k17user) {

            $tmp_check = $tmp_project->susers()->where('email', $k17user->email)->first();
            if (!count($tmp_check)) {
                $user = new \App\Suser();
                $user->name = $k17user->name;
                $user->email = $k17user->email;
                $user->phone = $k17user->phone;
                $user->expires = $k17user->expires;
                $user->password = $k17user->password;
                $user->password_raw = $k17user->password_raw;
                $user->rand = str_random(16);
                $user->level()->associate($tmp_level);
                $user->project()->associate($tmp_project);
                $user->save();

                $sub = "Интенсив Димы Ковпака - Доступы";
                $msg = "Здравстуйте, {username}!\r
Ваши доступы к интенсиву:\r
Ссылка:\r
http://intensiv2016.abckabinet.ru/login\r
\r
Email:\r
{email}\r
\r
Пароль:\r
{pass}\r
\r
Проблемы с доступом? Пишите:\r
support@abckabinet.ru\r
\r
Благодарим Вас за покупку!\r
\r
-----------\r
\r";
                $msg = str_replace("{username}", $user->name, $msg);
                $msg = str_replace("{email}", $user->email, $msg);
                $msg = str_replace("{pass}", $user->password_raw, $msg);

                Mail::raw($msg, function($message) use ($user, $sub) {
                    $message->from('hostmaster@abckabinet.ru', 'ABC Кабинет');
                    $message->to($user->email)->subject($sub);
                });
            }
        }
    });

    Route::get('/intense', function() {
        $project = \App\Project::findOrFail("intensiv2016");
        $users = $project->susers;

        foreach ($users as $user) {
            $sub = "Интенсив Димы Ковпака - Доступы";
            $msg = "Здравстуйте, {username}!\r
Ваши доступы к интенсиву:\r
Ссылка:\r
http://intensiv2016.abckabinet.ru/login\r
\r
Email:\r
{email}\r
\r
Пароль:\r
{pass}\r
\r
Проблемы с доступом? Пишите:\r
support@abckabinet.ru\r
\r
Благодарим Вас за покупку!\r
\r
-----------\r
\r";
            $msg = str_replace("{username}", $user->name, $msg);
            $msg = str_replace("{email}", $user->email, $msg);
            $msg = str_replace("{pass}", $user->password_raw, $msg);

            Mail::raw($msg, function($message) use ($user, $sub) {
                $message->from('hostmaster@abckabinet.ru', 'ABC Кабинет');
                $message->to($user->email)->subject($sub);
            });
        }
    });

    Route::get('ipr', function() {
        $keys = Array(
            "CRT4-7G2Q-CYRZ",
            "TJ7W-8A7X-C7R7",
            "SKN3-7H4C-C9R6",
            "PDUU-988K-C2R3",
            "YL6M-8W39-CCRZ",
            "UZYD-BRYN-B8YZ",
            "N89S-668Z-CWRZ",
            "LWMV-B9YC-ALZZ",
            "9VYK-9Y4N-CKRN",
            "TWM9-9N2B-CLR7",
            "FXZZ-BDYP-BMYT",
            "UUYA-BNYJ-A8ZZ",
            "6Y2H-5V9R-CMRK",
            "UKK6-7K69-C9R8",
            "ZUB8-8M4J-CDRZ",
            "7CQZ-8DAG-ZLCY",
            "WSSE-BSYH-AHZA",
            "TTVD-BRYK-A7ZZ",
            "9LHL-6Z86-CARN",
            "JQQ7-7L8F-CFRX",
            "3EQZ-7D8G-C3RH",
            "QB9C-5Q8Y-CZR3",
            "VD7F-6T6W-C9RZ",
            "MLRG-8U6G-CARZ",
            "SHLY-BCYB-A6Z6",
            "8GTJ-7X25-CMRY",
            "9Z8W-8A4Y-CNRN",
            "P5UE-7S4J-CUR2",
            "VGC5-6J45-C9RZ",
            "3SAG-5U7Z-CGRH",
            "GP9W-8A2Z-CDRU",
            "QJ9P-739Z-C7R4",
            "PT3V-898T-CHR3",
            "JG34-4S24-CXRY",
            "HSVU-B8YL-AHZV",
            "FGY4-6N95-CTRY",
            "FNWY-BCYM-ACZT",
            "84PP-632F-CTRL",
            "6V2Y-7C3S-CJRK",
            "D4VC-6QAK-ZTCQ",
            "YC54-5U5C-CZRZ",
            "5SKJ-7X29-CHRJ",
            "UBT2-7G28-CZRZ",
            "UYDE-9SB2-YNC8",
            "3TPT-876F-CHRY",
            "CVFY-9C25-CKRQ",
            "4ZCG-6U7Y-CZRA",
            "S3ZR-953P-CSR5",
            "Y76K-6Y7V-CVRB",
            "8HXS-866N-C6RM",
            "W83T-772T-CWR9",
            "YC6C-6Q4V-CCRZ",
            "T6YF-8T4N-CVR6",
            "J7Z5-6J6W-CWRY",
            "GXZY-BCYP-BMYU",
            "V72L-6ZBR-YVC8",
            "NV8J-8XBX-YJCZ",
            "C37H-3V9W-CRRP",
            "XZCT-B7Y2-ABZZ",
            "46TC-5QBV-YHCY",
            "24Y5-4J5N-CTRF",
            "PBCR-7552-C3RZ",
            "6749-2N6T-CVRJ",
            "3K5H-4V5U-C8RH",
            "G5H7-4L56-CURT",
            "HER2-6GAG-Z3CV",
            "2HYU-8836-CGRY",
            "DGN8-6MAC-Z5CR",
            "UTR6-9K2G-C8RZ",
            "R4KL-7Z29-CTR4",
            "MLGW-9AB6-YACZ",
            "N2SC-6Q5H-CRRZ",
            "QJUD-8R8J-C8R4",
            "5GZ6-6K25-CJRY",
            "8XHR-8557-CMRM",
            "X73D-5R6S-CVRA",
            "PVR5-8J8G-CKR3",
            "PKYP-B3Y9-A3ZZ",
            "7LSW-8A8A-CLRY",
            "4GNH-6VAC-Z5CY",
            "C3JZ-6D99-CSRP",
            "8GXS-865N-C5RM",
            "47LZ-6D7B-CWRH",
            "6UXE-8S3M-CJRK",
            "YXTA-BMYC-AZZZ",
            "EC4Q-546U-CSRY",
            "LDB8-5M32-CZRY",
            "BYD4-622N-CPRY",
            "Q9WY-BCYM-AYZ3",
            "G88X-6B5Y-CWRT",
            "E2AB-3P7Z-CQRR",
            "V8KR-856A-CXR8",
            "ZXVY-BCYL-3M3D",
            "M38T-672Y-CRRZ",
            "DQ8H-6V4X-CERR",
            "56BR-459V-CYRZ",
            "9KH8-5M46-C9RN",
            "5G49-3N4T-C4RJ",
            "28UL-6ZBJ-YXCF",
            "7URY-9C8H-CJRL",
            "Y4WL-9ZBL-YTCB",
            "RC9D-6RBY-Y5CZ",
            "2MFR-6565-CBRG",
            "LVHZ-BDY7-AKZZ",
            "TBMH-7V9B-C7RZ",
            "DKMZ-9DAC-Z9CR",
            "T769-5NBV-YVC6",
            "HGXE-8SAM-Z5CV",
            "V3JA-683S-C8RZ",
            "G4DB-4P42-CTRT",
            "JUHY-BCY7-AJZX",
            "ZH4T-875U-C5RD",
            "Y84X-7B9U-CWRB",
            "6E6Q-542W-C2RK",
            "28JG-4U58-CXRF",
            "8G7G-4U7W-C4RM",
            "P8HF-6T56-CXR2",
            "MKUH-8V9J-C9RZ",
            "SMGG-8U25-CBR6",
            "RRLC-8Q7A-CGR5",
            "VWJJ-BXY8-ALZ9",
            "S4ZG-8U3T-C5RZ",
            "999C-3Q9Y-CXRM",
            "YAWR-B5YM-AZZB",
            "KRXQ-B4YN-AGZY",
            "E65V-596V-CURR",
            "6B6Q-449W-CZRJ",
            "F55M-4V7T-CSRY",
            "6QHK-6Y96-CFRK",
            "GA32-3GBS-YYCT",
            "B2J7-3L98-CRRY",
            "GJMC-6Q9B-C8RU",
            "CK87-4L7X-C8RQ",
            "2GQK-6Y4F-C5RG",
            "T8DE-6S42-CXR6",
            "BSV8-7M8K-CHRP",
            "H7Q2-5G2F-CWRU",
            "WYJC-9Q78-CNRA",
            "KD85-4J6X-CYRY",
            "KBL9-6NBA-YYCY",
            "2HTK-6Y86-CGRY",
            "AMZN-9PAB-ZYCZ",
            "AATL-7ZAZ-ZNCY",
            "MEU7-7L3J-C3RZ",
            "YA8P-737Y-CYRB",
            "99Y3-5H5N-CYRM",
            "HNL4-6A5C-CVRY",
            "XUEW-BAY4-AJZB",
            "HR64-5V4F-CVRY",
            "BH4W-6A4U-C5RP",
            "7ZYY-BCYL-BYYZ",
            "KQQM-9G4F-CYRY",
            "MYVZ-BDYL-2N2Z",
            "HPP2-6G9E-CERV",
            "MMQS-968G-CBRZ",
            "K89C-4Q9Y-CWRX",
            "K952-3G6U-CXRX",
            "QQFY-BCY5-AFZ4",
            "Q87G-5U7W-CWR3",
            "6VMW-9ABC-YKCK",
            "8FLS-762B-C4RM",
            "WB6B-6PAV-ZZC9",
            "GFVT-97BL-Y4CU",
            "ZYU7-BLYJ-ANZD",
            "V3BP-73AS-Z8CZ",
            "Z9D9-6N62-CYRC",
            "LZZQ-B4YP-BZYY",
            "HFBV-7944-CVRY",
            "ZEUQ-B4YK-A3ZD",
            "GK3H-5V6S-C8RU",
            "9UXG-8U8M-CJRN",
            "RPW8-9M2L-CER5",
            "U8XU-B8YN-AXZ7",
            "6YHY-9CB7-YNCK",
            "P3WT-879M-CSR2",
            "YSAB-8P3Z-CGRC",
            "4ZTP-933J-CYRZ",
            "7T8E-5S8X-CHRL",
            "6B4S-469U-CZRJ",
            "XM42-6GBT-YACB",
            "BBV2-5G5K-CPRY",
            "6SBQ-74BH-YKCY",
            "S8BD-6RAX-Z5CZ",
            "8Z97-5L9Y-CNRM",
            "V4QW-9A3G-CTR8",
            "PE3F-5T7S-C2R3",
            "R3MY-8C6C-CSR4",
            "NPR4-7G9E-CZRZ",
            "TSW9-9N8L-CHR7",
            "ADYE-7SBN-Y2CY",
            "H7RD-6R4G-CWRU",
            "VX5U-989V-CLR9",
            "HEPN-7F93-CVRY",
            "FVWC-9QAL-ZKCT",
            "UTHV-B9Y7-A8ZZ",
            "W52Y-7C3S-CTR9",
            "DNJ6-6KB8-YCCR",
            "QZTA-B4YZ-AZZA",
            "ZST7-9L9H-CDRZ",
            "DF3L-5Z2S-C3RR",
            "476W-4A9W-CVRH",
            "XLTJ-BXYA-ABZZ",
            "A838-2M9S-CWRN",
            "43HY-5C87-CSRH",
            "JT5S-86BV-YHCX",
            "GUHS-96B7-YJCU",
            "ENH6-6KA6-ZCCS",
            "L8FY-7C85-CXRY",
            "CCUE-6S8J-CQRY",
            "2XZ2-7G2M-CGRY",
            "A2VA-5K3R-CNRY",
            "5C77-3LBW-YJCY",
            "E74L-4Z6T-CVRR",
            "K7HJ-6X36-CWRX",
            "A9J8-4M68-CYRN",
            "BJV3-6H4K-C8RP",
            "ZRV4-9K7G-CDRZ",
            "4MJ9-5N48-CBRY",
            "TTQQ-B4YG-B7YZ",
            "8ZYE-9SBN-YMCY",
            "F5ZZ-9DAP-ZUCS",
            "T2PU-886F-CRR6",
            "GT98-6M2Y-CHRU",
            "ZYAY-BCYN-BDYZ",
            "APJ7-6LB8-YECY",
            "HXBD-7R4M-CVRY",
            "W4X3-7H2M-CTR9",
            "FP2J-6XBR-YDCT",
            "9UTU-988J-CJRN",
            "XJZ9-9N68-CBRZ",
            "RHYD-9RBN-Y6C5",
            "TPRA-9GBE-Y7CZ",
            "LK8H-6V6X-C8RZ",
            "MDX2-7GAM-Z2CZ",
            "JAUK-7Y9J-CZRW",
            "H9V2-5G9K-CYRU",
            "CZZ6-8K8Q-CYRZ",
            "K2WY-8C8M-CRRX",
            "RD9E-6S3Y-C5RZ",
            "4WRW-9A5H-CLRY",
            "JMWA-8L3B-CXRY",
            "MVXG-BUYM-AKZZ",
            "FLY5-7J5N-CART",
            "DWZ8-8M8L-CRRY",
            "S84X-7B3U-CWR5",
            "WGND-8R4C-C5RA",
            "DV26-5K2R-CJRR",
            "7SMR-854C-CHRL",
            "W283-4H5X-CQR9",
            "NDF3-5H44-C2RZ",
            "VMNS-B6YD-ABZ9",
            "HY9L-8ZBY-YMCV",
            "8364-2VBR-YLCY",
            "JDJ4-5852-CXRY",
            "X8RN-9HBX-YACZ",
            "X2VD-7R9K-CRRA",
            "K2ZW-8A9P-CRRX",
            "VVN3-8H8C-CKR9",
            "DFRU-885H-C4RR",
            "PXQF-9T9F-CMR3",
            "9S5L-6Z3U-CGRN",
            "UYAP-939N-C8RZ",
            "AD79-3N9W-CYRZ",
            "SAY6-7K8N-CZR5",
            "HMXD-8R5M-CBRV",
            "QG9S-769Z-C4R4",
            "8WP9-7N4E-CLRM",
            "BX7D-6R4W-CLRP",
            "N944-4TAX-ZZCZ",
            "PV7E-7S7W-CJR3",
            "NBZ7-7L6Z-CZRA",
            "ML9L-7Z3Y-C9RZ",
            "M2LP-73AB-ZRCZ",
            "X5GD-6R75-CURA",
            "DKAG-5U9Z-C8RR",
            "L3XZ-9D2N-CSRY",
            "APRP-837H-CERY",
            "2BD2-2G82-CGRY",
            "45UD-5R2J-CURH",
            "5QQQ-843G-CFRJ",
            "DW7G-6U8W-CKRR",
            "2Q6L-5Z5V-CERG",
            "8MJJ-6X88-CBRM",
            "2LRY-8C4H-CARG",
            "2CKR-65BA-YGCY",
            "VUFX-BBY5-AJZ9",
            "E4YY-8C6T-CRRY",
            "7NDY-7C73-CCRL",
            "R4A4-4Z5S-C4RZ",
            "F96N-5W3X-CSRY",
            "P46W-6A7W-CSR2",
            "M5D2-4G22-CURZ",
            "AUN6-6K9C-CJRY",
            "WRUW-BAYK-2GBA",
            "TLDR-95A3-ZAC7",
            "H8Q9-6NAF-ZXCU",
            "GPAC-6Q3Z-CDRU",
            "DZ2D-6R3R-CNRR",
            "EWEW-9A24-CLRS",
            "D2S9-5N2H-CRRQ",
            "3277-BLYW-9QCG",
            "P76P-633W-CVR2",
            "8HFT-6795-C6RM",
            "RVR7-9L2G-CKR5",
            "U7NN-8D3W-C7RZ",
            "QNLZ-BDYB-ACZ4",
            "S7PK-8YAE-ZWC5",
            "WQ97-7L4Y-CERA",
            "3GXT-87BN-Y5CH",
            "43P4-3E6S-CHRY",
            "CQBD-6R2F-CQRY",
            "EFNJ-7XBC-Y4CS",
            "WRSK-BYYH-AGZA",
            "7S72-4G4W-CGRL",
            "X7EG-7UA3-ZWCA",
            "8BXR-759N-CMRY",
            "SG9Y-8C7Z-C4R6",
            "6MWV-99BM-YBCK",
            "DE9L-5Z7Y-C2RR",
            "FBW4-6L2T-CYRZ",
            "29XC-5Q6M-CYRF",
            "3QKH-6V69-CFRH",
            "V9W9-8NBL-YYC8",
            "WKZK-BYY9-AAZZ",
            "LHXU-B8YN-A6ZZ",
            "NHL6-6K7A-C6RZ",
            "LBUG-7U8J-CZRY",
            "HE5C-4Q8U-C2RV",
            "NCVK-8Y6K-CZRZ",
            "ZVG6-8K85-CKRD",
            "K57F-4T7W-CTRX",
            "R898-5M2Y-CWR4",
            "X4C3-5H2T-CARZ",
            "L2EN-64AR-ZYCY",
            "D7WP-737M-CWRQ",
            "RNP2-7G7E-CCR5",
            "P44H-5VAT-ZSC2",
            "9SCT-7782-CHRN",
            "TCHR-8557-C7RZ",
            "ZXBU-B8YM-ADZZ",
            "5FCS-66A2-Z4CJ",
            "P3UV-899K-CSR2",
            "LSLD-8R3A-CHRZ",
            "34R9-4N3G-CTRG",
            "8K32-3G3S-C8RM",
            "W8MZ-9D7C-CXR9",
            "M5YJ-8XAN-ZUCZ",
            "ZRRB-BPYG-AGZD",
            "C9UV-892K-CYRP",
            "ASJJ-7X68-CHRY",
            "BCZA-6P8Y-CZRA",
            "8CGV-6976-CMRY",
            "GPEA-635E-CURY",
            "P9CT-7752-CYR2",
            "MUGQ-9446-CJRZ",
            "KRWL-BZYL-AGZY",
            "YG9S-867Z-C4RC",
            "TVFL-9Z64-CKR7",
            "JESW-9A33-CXRY",
            "PUVN-BLYJ-A3ZZ",
            "9U97-5L5Y-CNRY",
            "H4CD-4R6T-CURY",
            "QERY-BCYH-A3Z4",
            "ZGH7-7L56-C5RD",
            "M2HY-7C57-CRRZ",
            "BAN8-5M2C-CZRY",
            "F2N5-4J5C-CRRS",
            "XMF6-7K64-CBRB",
            "Z5Q5-7JBF-YUCC",
            "5AE3-3H23-CZRY",
            "72TB-4P9R-CKRY",
            "894J-4XAT-ZXCL",
            "Q22G-4U6R-CQR3",
            "T8ZB-8P3X-C6RZ",
            "UECC-6Q83-C8RZ",
            "J2D2-3G62-CRRW",
            "UDDT-8753-C2R8",
            "WXUB-BPYJ-AMZA",
            "EGMG-6U8B-C5RS",
            "N8TJ-7X9X-CZRZ",
            "5ZRP-932H-CJRY",
            "LUV7-8L9K-CJRZ",
            "KEC6-5K23-CYRY",
            "942N-3S8S-CMRY",
            "BVP5-7J2E-CKRP",
            "MLGX-9B26-CARZ",
            "LTE7-7LB3-YZCY",
            "9MEK-6Y53-CBRN",
            "SCF9-6N44-C6RZ",
            "NCZJ-8X9Z-CZRA",
            "SM9F-7T4Y-CAR6",
            "GZTF-9T5U-CYRZ",
            "HYD9-7N32-CNRV",
            "L2Q6-5K5F-CRRY",
            "UWYF-BTYN-BLY8",
            "49L6-4KAA-ZYCH",
            "3VBU-785K-CHRY",
            "MB4R-654U-CZRZ",
            "QSLZ-BDYB-BHY4",
            "7L67-4LBV-Y9CL",
            "M6W8-6M8L-CVRZ",
            "NG7N-6X94-CZRZ",
            "C8A9-3N9Z-CWRP",
            "ANSD-7R4H-CCRY",
            "YUXM-BNYJ-BCYZ",
            "D6ET-6724-CVRQ",
            "FFU3-6H3J-C4RT",
            "67UU-783K-CWRJ",
            "ADS7-5L8H-C2RY",
            "ZEY3-8H6N-C3RD",
            "57WN-6M7W-CYRZ",
            "L87Z-7DBX-YWCY",
            "74SH-5V6H-CTRK",
            "X8JX-9B39-CXRA",
            "D7GC-4Q85-CWRQ",
            "APFL-7ZB4-YECY",
            "U5BM-6U87-CZRZ",
            "BN6A-5VAB-ZPCY",
            "7RK7-6LB9-YGCL",
            "V6HC-6Q66-CVR8",
            "WWFV-B9Y5-BLYA",
            "QRNK-9Y6C-CGR4",
            "SXPH-BVYE-AMZ6",
            "U88S-764Y-CWR7",
            "LKHX-9BB7-Y9CZ",
            "MBPC-7QAE-ZZCZ",
            "9PMJ-7X5B-CERN",
            "Y3VJ-8X7K-CSRB",
            "F87D-4R3W-CWRS",
            "BQX8-7M8M-CFRP",
            "LZ86-7KAX-ZNCZ",
            "M829-4NBR-YWCZ",
            "8BF4-348M-CYRZ",
            "LG8G-6UBX-Y4CZ",
            "3AQ6-4K5F-CZRG",
            "8SVR-954L-CHRM",
            "ZX8R-B5YY-ALZD",
            "WS7W-9A9X-CGRA",
            "KM93-5H4Y-CARY",
            "ADMQ-74BC-Y2CY",
            "9JER-6594-C8RN",
            "B5M9-4N7B-CURY",
            "DR9M-7ZBF-YRCY",
            "28W9-5NBL-YXCF",
            "3B53-2H2U-CZRG",
            "U8QU-984G-CXR7",
            "JK9Z-8D3Z-C8RX",
            "UXRG-BUYG-AMZ8",
            "6U6F-5T7V-CKRY",
            "P2U5-6J2J-CRR2",
            "3QHQ-7427-CFRH",
            "ZC9M-7Z8D-CZRZ",
            "LRSK-9Y6H-CGRZ",
            "Q4LC-6Q3A-CTR3",
            "BTLS-869B-CPRY",
            "VNRJ-BXYG-ACZ9",
            "3PKT-777A-CERH",
            "KKMW-9A4C-C9RY",
            "AMWR-95BM-YBCY",
            "DPSS-964E-CRRY",
            "9Z6S-768W-CNRN",
            "QVT7-9L3K-C4RZ",
            "2TGS-7656-CGRY",
            "E24B-3PBT-YQCR",
            "WFG2-6G55-C4RA",
            "76L6-4KAA-ZVCK",
            "QYRE-BSYG-ANZ4",
            "8GWH-7V3L-C5RM",
            "DR98-5M7Y-CFRR",
            "W2AG-6UAZ-ZQC9",
            "P7J9-6NA8-ZWC2",
            "HMUM-9KBB-YVCY",
            "8TTR-953J-CMRY",
            "4KXL-7Z8M-C9RY",
            "TWTH-BVYL-A7ZZ",
            "V4VF-8TBK-YTC8",
            "ZPKU-B8YA-BEYD",
            "ZAGD-7R45-CZRC",
            "6QSR-857F-CKRY",
            "4884-2X4W-CHRY",
            "69B3-2H9Y-CJRY",
            "NEM5-6J4B-C3RZ",
            "Y5V2-7G2K-CURB",
            "CALV-794B-CZRP",
            "RWMA-9BBL-Y5CZ",
            "VMVA-9K4B-C9RZ",
            "4LTM-7J6A-CYRZ",
            "KZR4-8G6Y-CYRZ",
            "XU2H-8V2R-CBRZ",
            "K6EG-5U63-CVRX",
            "MQPH-9VAE-ZFCZ",
            "VLND-8R8C-CAR9",
            "R9TL-8Z6Y-C4RZ",
            "S2QN-7G9R-C5RZ",
            "L5T9-6N4U-CYRY",
            "4V4L-6ZAT-ZJCY",
            "DHZG-8UB6-YRCY",
            "FMUR-954K-CBRT",
            "GVDN-833K-CURY",
            "JXTC-9Q3M-CXRY"
        );
        
        foreach($keys as $key){
            $ipr = new \App\Ipr();
            $ipr->key = $key;
            $ipr->save();
        }
        
//        $pr1 = \App\Project::findOrFail("k17")->susers;
//        $pr2 = \App\Project::findOrFail("intensiv2016")->susers;
    });
});
