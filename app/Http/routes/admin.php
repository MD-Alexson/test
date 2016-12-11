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

        echo getAvaibleIprCount() . "<br/><br/>";

        $pr1 = \App\Project::findOrFail("k17")->susers;
        foreach ($pr1 as $user) {
            if (!strlen($user->ipr_key)) {
                $key = getAvaibleIprKey();
                if ($key) {
                    $user->ipr_key()->associate($key);
                    $user->save();
                } else {
                    echo "NO KEY FOR USER " . $user->id . "<br/>";
                }
            }
        }

        $pr2 = \App\Project::findOrFail("intensiv2016")->susers;
        foreach ($pr2 as $user) {
            if (!strlen($user->ipr_key)) {
                $key = getAvaibleIprKey();
                if ($key) {
                    $user->ipr_key()->associate($key);
                    $user->save();
                } else {
                    echo "NO KEY FOR USER " . $user->id . "<br/>";
                }
            }
        }

        echo "Keys added successfully<br/><br/>";
        echo getAvaibleIprCount();
    });

    Route::get('/ipr2', function() {

        function getNextKey() {
            $key = \App\Ipr2::first();
            $result = $key->key;
            $key->delete();
            return $result;
        }

        echo \App\Ipr2::count();

        $susers1 = \App\Level::findOrFail(10146)->susers;
        foreach ($susers1 as $user) {
            if (!strlen($user->ipr_key2)) {
                $user->ipr_key2 = getNextKey();
                $user->save();
                echo $user->name . " - " . $user->ipr_key2 . "<br/>";
            }
        }
        $susers2 = \App\Level::findOrFail(10147)->susers;
        foreach ($susers2 as $user) {
            if (!strlen($user->ipr_key2)) {
                $user->ipr_key2 = getNextKey();
                $user->save();
                echo $user->name . " - " . $user->ipr_key2 . "<br/>";
            }
        }
        echo "<br/>";
        echo \App\Ipr2::count();
    });

    Route::get('/ipr3', function() {
        $keys = Array(
            "XCP8-7M8E-CBRZ",
            "B5YE-6S4N-CURY",
            "TWNW-BAYD-BLY7",
            "T6Q9-7NAF-ZVC6",
            "UVPL-BZYE-AKZ8",
            "WYXB-BPYM-BNYA",
            "72NQ-548D-CRRK",
            "GEN3-5H6C-C3RU",
            "3BF6-3K54-CHRY",
            "E4TZ-8D2J-CTRR",
            "NK3T-775T-C8RZ",
            "UUU2-9G2J-CJR8",
            "H3LS-669B-CSRU",
            "W5P3-6H5E-CUR9",
            "6QZY-BCYP-AFZK",
            "GY2A-6R2M-CURY",
            "63QE-4S9F-CSRJ",
            "X67R-753X-CURA",
            "ZKER-9564-C9RD",
            "SM2W-8A4S-CAR6",
            "TTC4-774Z-CZRA",
            "XKJ5-7J78-C9RB",
            "8MFK-6Y54-CBRM",
            "L3X8-6M5M-CSRY",
            "457H-3V3W-CTRH",
            "DDEW-7A24-C2RR",
            "5M99-4N5Y-CARJ",
            "GP2W-7A5S-CDRU",
            "9RUN-8K9G-CNRY",
            "TLEA-734A-C7RZ",
            "GS9R-85AZ-ZGCU",
            "24JL-4Z68-CTRF",
            "TNWL-BZYL-ACZ7",
            "27F4-248W-CFRY",
            "LP3H-6V6S-CDRZ",
            "DQV5-7J5K-CFRR",
            "FHMF-6T9B-C6RT",
            "TC7R-755X-C7RZ",
            "RBUC-8QAJ-Z5CZ",
            "MJGU-8876-C8RZ",
            "2HFB-4P54-C6RG",
            "AV7X-8BBX-YJCY",
            "XNVG-BUYK-ACZB",
            "JRYZ-BDYG-BXYY",
            "7BVN-7L2L-CYRZ",
            "7L9K-5Y7Y-C9RL",
            "29UV-792K-CYRF",
            "Y4YT-B7YT-ABZZ",
            "M8NQ-749D-CXRZ",
            "THG3-6H55-C6R7",
            "7KH4-4689-CLRY",
            "7WY7-8LAN-ZLCL",
            "EKLX-8B8B-C9RS",
            "DACY-6C92-CZRQ",
            "NZXC-BQYM-AZZZ",
            "SSTJ-BXYH-A6ZZ",
            "A9W3-5H4L-CYRN",
            "SSAL-8Z7Z-CGR6",
            "22CF-3TBR-YFCY",
            "9LGS-7646-CARN",
            "QWCV-B9Y2-ALZ4",
            "QN6D-6R8V-CBR4",
            "4EUX-8BBK-Y3CY",
            "SH7A-6W25-C6RZ",
            "WYBV-B9YN-AAZZ",
            "6D4Q-449U-CKRY",
            "TM3A-6S4A-C7RZ",
            "QCAE-6S2Z-C4RZ",
            "FSSC-8Q3H-CHRT",
            "BURE-8S2G-CJRP",
            "587B-3PBW-YWCY",
            "J4ND-5R9C-CTRW",
            "959L-4Z4Y-CTRM",
            "2K4H-4V3T-C8RG",
            "X7H9-6N66-CWRA",
            "A99D-4RBY-YXCN",
            "2N7G-4U8W-CBRG",
            "5WGD-6R65-CLRJ",
            "G6FZ-7D25-CVRT",
            "TZGW-BAY6-B7YZ",
            "RGLJ-8X3A-C5R5",
            "RDB4-5255-CZRZ",
            "AGNS-767D-C5RY",
            "7W57-5LBU-YKCL",
            "56CY-5C72-CVRY",
            "Y3FZ-8D75-CSRB",
            "7T7F-5T8W-CHRL",
            "HRZ5-8J4G-CVRY",
            "5H4A-3T65-CJRY",
            "6YQ9-7N5F-CNRK",
            "X2UG-8UBJ-YRCA",
            "9NM6-6KAB-ZCCN",
            "B6KQ-643A-CVRY",
            "LQ5U-882V-CERZ",
            "VT6D-7R9V-CHR9",
            "ACCH-5VBY-YZCA",
            "FVWR-B5YM-AKZT",
            "GY4L-7Z5T-CMRU",
            "N4NH-6V7C-CTRZ",
            "K94K-5Y3T-CXRX",
            "X6J8-6M68-CVRA",
            "8S3R-656T-CGRM",
            "5DDM-5332-CJRY",
            "3KHA-56A9-ZHCY",
            "ETE7-6L43-CSRY",
            "KT8D-7RAX-ZHCY",
            "9248-2M3T-CQRM",
            "VPX8-9M7M-CER9",
            "4WR8-7MBG-YLCY",
            "CS7C-5Q9W-CGRQ",
            "WDVQ-B4YL-A2ZA",
            "UEJX-9B69-C3R8",
            "P67B-4P9W-CUR2",
            "THBV-8986-C7RZ",
            "ZPLM-BBYE-ADZZ",
            "RDFP-83A5-Z2C5",
            "GLCP-7342-CARU",
            "N6KQ-745A-CVRZ",
            "MF3B-5PBS-Y3CZ",
            "PUMD-9RAB-ZJC3",
            "4BRZ-7D7H-CYRZ",
            "AH57-3L9U-C5RY",
            "7ARW-7A6H-CZRK",
            "J2QS-765G-CRRW",
            "7FEF-5TB3-Y4CL",
            "X4SK-8Y5H-CTRA",
            "CR55-4J9U-CFRQ",
            "7KYH-7V8N-C9RL",
            "F2VP-733L-CRRS",
            "JYAL-8Z4Z-CMRX",
            "7CQE-5S9F-CLRY",
            "37CM-424W-CGRY",
            "N9VR-95AL-ZYCZ",
            "L65L-5Z3U-CURY",
            "2UP6-6K3E-CJRG",
            "PVK3-7H99-CKR3",
            "4M6N-5W5A-CYRZ",
            "U4YD-8RBN-YTC7",
            "KAFS-7635-CZRX",
            "NKSC-8Q3H-C9RZ",
            "9D2W-5A6S-CNRY",
            "S2AQ-646R-C5RZ",
            "3WER-7564-CLRH",
            "TJ8X-8B9Y-C7R7",
            "ZFX6-8K9M-C4RD",
            "WY6F-8T7V-CMRA",
            "9YAS-86BN-YNCY",
            "XDPS-969F-C2RB",
            "L4UQ-84BK-YTCY",
            "R9U9-7N5J-CYR4",
            "EXUF-9T2J-CMRS",
            "T8NP-835D-CXR6",
            "D2M5-4J2B-CRRQ",
            "VHDD-7R42-C6R9",
            "JM79-5N7W-CARX",
            "RT9W-9A7Z-CHR5",
            "NRC9-7NBG-YZCZ",
            "35KA-398U-CGRY",
            "MC63-4H3V-CZRZ",
            "DYPQ-948F-CNRR",
            "6MHH-6V26-CBRK",
            "6H48-3M5T-C5RK",
            "JWE8-7M33-CLRX",
            "5NHU-7857-CCRJ",
            "BJSG-7U4H-C8RP",
            "W6RX-9B8H-CVR9",
            "A3KF-4T89-CSRN",
            "T9QJ-8X3F-CYR6",
            "6SR2-6G3G-CHRK",
            "EZCP-8362-CSRY",
            "6KSG-7UAH-Z9CK",
            "5FPJ-6X4E-C4RJ",
            "UG9J-7X4Y-C4R8",
            "AWFD-7RA4-ZLCY",
            "RLVE-9S3K-CAR5",
            "TRBQ-943G-C7RZ",
            "DL8X-7B5Y-C9RR",
            "63VA-5KAS-ZJCY",
            "KUWB-9P3L-CJRY",
            "DC9J-5X3Y-CRRY",
            "GGG5-5J35-C5RU",
            "4XRT-973H-CMRY",
            "8UQU-984G-CJRM",
            "CLBM-6A6Q-CYRZ",
            "CGYX-9B55-CQRY",
            "Q54J-5X4T-CTR3",
            "GYTX-BBYJ-BNYU",
            "ZNVM-BLYC-BDYZ",
            "B6XU-88AN-ZVCY",
            "FY3W-8A4T-CMRT",
            "G6ZU-887P-CVRT",
            "7CXS-86AN-ZLCY",
            "MYEE-8S43-CNRZ",
            "VJ62-5G8V-C7R9",
            "L7K4-592W-CYRY",
            "YR3K-8Y4S-CFRC",
            "Q7DJ-6X52-CWR3",
            "MG8U-786Y-C4RZ",
            "WLVV-B9YL-BAYA",
            "VNP5-8J4E-CCR9",
            "EVER-8564-CKRS",
            "RBB7-5L65-CZRZ",
            "CGM8-5M8B-C5RQ",
            "5XT3-7HAM-ZJCY",
            "DCAG-5UBZ-YRCY",
            "JHNB-7PAC-Z6CX",
            "MAYF-8TBN-YZCZ",
            "QHZQ-B4YP-A6Z4",
            "EVSK-9Y3H-CKRS",
            "4WZB-8P2L-CYRZ",
            "UH2B-6PAR-Z5C8",
            "V8TJ-8X7X-C8RZ",
            "EY3H-6V8S-CMRS",
            "LWM2-7G7B-CLRZ",
            "2P9N-5Z9D-CGRY",
            "H99T-674Z-CXRU",
            "CBEJ-5X63-CQRY",
            "KJQ4-6F98-CYRY",
            "D7Q8-5M4F-CWRQ",
            "N24S-567U-CQRZ",
            "Z2CE-6S3R-CCRZ",
            "MFQ6-6K9F-C4RZ",
            "MUYC-9Q8N-CJRZ",
            "QQMZ-BDYC-AFZ4",
            "H8NB-5P9C-CXRU",
            "TFBE-6S94-C7RZ",
            "FQKF-7T69-CFRT",
            "MMQ5-7J5F-CBRZ",
            "VHPV-B9YF-A6Z9",
            "36YK-6Y3N-CVRG",
            "QJYQ-B4Y8-A4ZZ",
            "7F4D-3R9T-C3RL",
            "HW79-6N5W-CKRV",
            "D8F7-4L34-CXRQ",
            "UX4J-8X6T-CLR8",
            "2SG3-4H95-CHRG",
            "QK9R-852Z-C8R4",
            "C22J-3X5R-CQRP",
            "SGNT-976D-C5R6",
            "ULL2-7G4A-CAR8",
            "UYN2-8G9C-CNR8",
            "B4SC-5Q5H-CTRY",
            "8CQP-73BG-YMCY",
            "WBXL-9Z7M-CARZ",
            "PRXR-B5YN-BGY3",
            "R7CF-6TBW-Y4CZ",
            "ACKB-5P39-CYRZ",
            "BCYH-7V4N-CPRY",
            "XPUE-BSYJ-AEZB",
            "4UAZ-7D9J-CYRZ",
            "PVEZ-BDY4-AKZ3",
            "VYFA-94AN-Z9CZ",
            "2WKD-6R79-CLRG",
            "JHQF-7T7F-C6RX",
            "ZYG3-8H85-CNRD",
            "ZR2G-8UAR-ZFCD",
            "W85B-5P6U-CWR9",
            "YEZ7-9LA3-ZCCZ",
            "CKNE-6S9C-C9RQ",
            "UGWZ-BDYM-B5Y8",
            "8M7K-5Y7W-CARM",
            "HH86-4K8X-C5RV",
            "W45V-792V-CSR9",
            "R463-4HAV-ZSC4",
            "FDAT-6772-CTRY",
            "WVNM-BDYK-AAZZ",
            "66H3-3H26-CVRJ",
            "88SM-6X6L-CYRZ",
            "6UVH-8V4K-CJRK",
            "SAJY-9CB9-YZC5",
            "2VHA-66AK-ZGCY",
            "VHYF-9T7N-C6R9",
            "U7AA-5Z7V-C7RZ",
            "RAVF-8T3K-CZR4",
            "RZ47-7L3T-CNR5",
            "RKEE-7S53-C9R5",
            "DWAG-7UBZ-YKCR",
            "8VPL-8Z5E-CKRM",
            "TTAY-BCY7-AZZZ",
            "BJFA-5458-CPRY",
            "6WQ2-6G6F-CLRK",
            "ADS7-5L8H-C2RY",
            "XPV7-9L6K-CERB",
            "R9KK-7Y69-CYR4",
            "SKWY-BCYM-B9Y6",
            "HLKJ-7X79-CARV",
            "NLY6-8K4N-CARZ",
            "KF3Z-7D3T-C3RY",
            "MUY9-9N5N-CJRZ",
            "MQGX-9B76-CFRZ",
            "TXPX-BBYF-2MA7",
            "26X2-4G3M-CVRF",
            "3QEM-645F-CHRY",
            "PAMN-8CAZ-Z2CZ",
            "WAC4-5Z89-CZRZ",
            "LK4F-6TAT-Z8CZ",
            "NGGC-6Q75-C5RZ",
            "8V55-4J9U-CJRM",
            "YC3H-6V6S-CCRZ",
            "S3YA-7N5S-C5RZ",
            "TLUP-B3YK-AAZ7",
            "TJGS-9626-C8R7",
            "YWBZ-BDYL-BCYZ",
            "3975-2J4W-CXRG",
            "6KZS-869P-C9RK",
            "MVQT-B7YG-AKZZ",
            "DCNS-766D-CRRY",
            "T9DH-6V82-CYR6",
            "7R2F-5TBR-YFCL",
            "7NEY-7C84-CCRL",
            "334A-2TAR-ZGCY",
            "ARGQ-7496-CGRY",
            "HPJS-8699-CERV",
            "D2NX-7BBD-YRCQ",
            "SKXD-9R4M-C9R6",
            "R5CP-6392-CUR4",
            "RNJ2-7GB8-YCC5",
            "82N2-3G5C-CRRL",
            "PR5T-876V-CFR3",
            "RSFM-952H-C5RZ",
            "GJW2-6G9L-C8RU",
            "T7XQ-945N-CWR6",
            "YFD5-6J72-C4RC",
            "RM24-5R5A-C5RZ",
            "NDT3-6H82-CZRZ",
            "L3M4-5BAS-ZYCY",
            "XVG7-8L75-CKRB",
            "ZR97-7L8Y-CFRD",
            "PEH5-6JB6-Y3C3",
            "S3SA-6H9S-C5RZ",
            "3LEJ-5X73-CARH",
            "ZZXY-BCYN-3D7Z",
            "596J-3X9V-CXRY",
            "CE8T-673Y-C2RQ",
            "KPXX-BBYN-BEYY",
            "7CS3-5HAH-ZLCY",
            "BRWP-935M-CGRP",
            "XDYA-9NA2-ZBCZ",
            "4JFY-7C25-C8RY",
            "HC5D-4R7U-CVRY",
            "9CDH-5VB2-YNCY",
            "Q6S5-6J5H-CVR3",
            "CG9J-5X6Y-C4RQ",
            "2VDG-6U22-CKRG",
            "UATT-978J-CZR7",
            "4E8S-564Y-C2RY",
            "HJDG-6U52-C8RV",
            "2ZYA-8NBG-YYCZ",
            "4T9R-659Z-CHRY",
            "WETX-BBYJ-A3ZA",
            "LE9J-6X3Y-C2RZ",
            "49GZ-6D46-CYRH",
            "B78W-5A8Y-CVRY",
            "PY5V-995V-CMR3",
            "UQRK-BYYG-AFZ8",
            "DM4E-5S3T-CARR",
            "DYX9-8N9M-CNRR",
            "6DAY-6C32-CKRY",
            "W2P4-6E3R-C9RZ",
            "XNMW-BAYC-BCYB",
            "ZGAW-9A35-CDRZ",
            "25AD-3RAZ-ZTCF",
            "4ZL4-6A4Y-CZRA",
            "PK93-5H7Y-C8R3",
            "N9HB-6PA6-ZYCZ",
            "GVX5-8J5M-CKRU",
            "LJC7-5L98-CZRY",
            "LTZ3-8H8Z-CYRZ",
            "VDHQ-8477-C2R9",
            "W5LR-855B-CUR9",
            "4V77-4L9W-CJRY",
            "XPBD-8R2E-CBRZ",
            "X8A5-5J6Z-CWRA",
            "RF6Q-744W-C3R5",
            "UMCJ-8X3B-C8RZ",
            "G9K5-5JA9-ZYCT",
            "K2WU-884M-CRRX",
            "SQWA-9L6F-C6RZ",
            "F3MD-5R3B-CSRS",
            "ZD6C-6Q6V-CDRZ",
            "WUEJ-9X53-CJRA",
            "FP8W-8AAY-ZDCT",
            "GJJ6-6KA8-Z8CU",
            "YYUN-BKYN-2CBZ",
            "QEFN-7583-C4RZ",
            "3TMU-884C-CHRY",
            "JBEW-7A64-CXRY",
            "CGVH-7V6K-C5RQ",
            "9W67-5L4V-CKRN",
            "U92N-6S4X-C7RZ",
            "4GZP-83AP-Z5CY",
            "P6AX-7B4V-C2RZ",
            "2JNZ-7D9D-C8RG",
            "8T9R-753Z-CHRM",
            "9NRJ-7X8G-CCRN",
            "6XJ2-6GA8-ZMCK",
            "54UL-6ZAJ-ZTCY",
            "B5US-764K-CURY",
            "8NHU-7887-CCRM",
            "4E98-3M5Y-C2RY",
            "FPSY-BCYE-ATZY",
            "PT62-6G2V-CHR3",
            "PTXJ-BXYM-A3ZZ",
            "K922-3G3R-CXRX",
            "XXXS-B6YN-2M7B",
            "3E3Z-5D5T-C2RH",
            "3YRK-8Y4G-CNRH",
            "T6R6-6K8G-CVR6",
            "W8ZE-8S9X-C9RZ",
            "YA4J-6X7T-CYRB",
            "GEUP-835K-C3RU",
            "L3E8-4M63-CSRY",
            "5B3D-3R2S-CZRY",
            "6K7S-66BX-Y8CK",
            "YGE8-7M23-C5RC",
            "69C3-3HAY-ZJCY",
            "K4DQ-6433-CTRX",
            "RJDW-9AB3-Y8C5",
            "Z2WL-9ZAL-ZRCC",
            "CNFF-6T54-CCRQ",
            "PDWR-957M-C2R3",
            "Z3VF-8T4K-CSRC",
            "9L6N-5W99-CNRY",
            "5EPM-6F63-CJRY",
            "GTHF-7T76-CURY",
            "SP7Z-9D5X-CDR6",
            "9QT4-6F8N-CYRZ",
            "G7TB-6P3W-CTRY",
            "GPH9-6N76-CERU",
            "KC2Z-6D9S-CYRY",
            "G495-3J4Y-CSRT",
            "E3U6-5K3J-CSRR",
            "T56H-5V7V-CTR6",
            "TNCN-827C-C7RZ",
            "SYBS-B6YN-A6ZZ",
            "M9JM-792Y-CZRY",
            "L8HC-5Q86-CXRY",
            "8X8G-6U5X-CLRM",
            "5E4N-4U62-CJRY",
            "QXNL-BZYC-AMZ4",
            "428J-3X3X-CQRH",
            "YT5A-7U8H-CCRZ",
            "R9YP-935Y-C4RZ",
            "F3CB-4PBS-YSCY",
            "YWVT-B7YL-2L6C",
            "FZZT-B7YP-BTYY",
            "V8E6-5K93-CXR8",
            "SB74-5WAZ-Z5CZ",
            "ASDY-8C53-CHRY",
            "3GVT-779L-C5RH",
            "ZX7L-9Z6W-CLRD",
            "SBNM-8D46-CZRZ",
            "526C-2Q5V-CQRY",
            "MF4C-5Q3T-C3RZ",
            "724E-2S7T-CQRK",
            "SHDD-7RB2-Y6C6",
            "N78V-699Y-CVRZ",
            "GVAP-832K-CURY",
            "T6RP-837H-CVR6",
            "MPAH-7V4Z-CDRZ",
            "LHWY-BCYM-A6ZZ",
            "YFGT-9746-C4RC",
            "U7M6-6K5B-CWR7",
            "5H99-4NAY-Z5CJ",
            "QL2Z-8D4S-C9R4",
            "UE73-5H4W-C2R8",
            "WBBL-7Z5A-CZRZ",
            "GFDT-7733-C4RU",
            "48CS-5622-CXRH",
            "64RU-687H-CTRJ",
            "VX3Q-943T-CLR9",
            "JVGW-9A86-CKRX",
            "JGC9-5N65-CXRY",
            "4W8A-5X4K-CYRZ",
            "EKV3-6H8K-C9RS",
            "YX44-7T5L-CCRZ",
            "NT4C-6Q8T-CHRZ",
            "D8ME-5S7B-CXRQ",
            "5E75-3JBW-Y2CJ",
            "3CJB-4P58-CHRY",
            "QZRD-BRYG-A4ZZ",
            "Q4YE-7S8N-CTR3",
            "4JBR-65B8-YYCZ",
            "CFNJ-6X9C-C4RQ",
            "XRQC-9Q8F-CGRB",
            "TGNN-9DB5-Y7CZ",
            "F7LK-6Y3A-CWRS",
            "NQMD-8R4B-CFRZ",
            "LVD3-6H82-CKRZ",
            "TKHL-8Z76-C9R7",
            "RCQ6-7KBF-Y5CZ",
            "KR45-5J6T-CFRY",
            "N7MY-8C6C-CWRZ",
            "CWEZ-9D34-CLRQ",
            "PBSL-8Z5H-C3RZ",
            "FNDQ-7473-CCRT",
            "85QY-7C3G-CURL",
            "AFYT-8784-CYRZ",
            "3KMN-6C89-CHRY",
            "V22Q-64BS-YQC8"
        );
        
        foreach($keys as $key){
            $ipr = new \App\Ipr3();
            $ipr->key = $key;
            $ipr->save();
        }
    });
});
