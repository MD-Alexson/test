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
        $keys = Array(
            "QTNM-BDY4-AZZZ",
            "CE9M-5Z72-CQRY",
            "3QQL-7Z6F-CFRH",
            "92VG-5U8K-CRRM",
            "JKDW-8A43-C9RX",
            "SUDQ-9473-CJR6",
            "WZKG-BUY9-AAZZ",
            "G6M6-5KAB-ZVCT",
            "2TNA-6C4G-CYRZ",
            "BGSK-7Y5H-C5RP",
            "D3RU-783H-CSRQ",
            "M44F-4T5T-CSRZ",
            "ZZRU-B8YH-2D7Z",
            "7SY8-7M7N-CHRL",
            "YYUV-B9YK-2N9C",
            "78D2-3GA2-ZXCK",
            "RS5H-7V7U-CGR5",
            "VU7C-8QAW-Z9CZ",
            "MVT5-8J7K-CZRZ",
            "BCBE-4S8P-CYRZ",
            "ZRLJ-BXYA-AGZD",
            "UGHG-7U96-C5R8",
            "CQM9-6N9B-CFRQ",
            "UD9K-7Y2Y-C8RZ",
            "Y3CF-6T4S-CBRZ",
            "E5CP-5362-CURR",
            "5UCT-7762-CJRJ",
            "YGZQ-B4YP-B5YC",
            "8N7W-7AAX-ZBCM",
            "ZBFR-8585-CDRZ",
            "S46V-699W-CSR5",
            "AREE-6S53-CGRY",
            "869Q-449Z-CURL",
            "3JBV-6948-CHRY",
            "9LWN-8M5A-CNRY",
            "JM4N-6U8A-CXRY",
            "H4QE-6SBF-YTCU",
            "NVSU-B8YK-BZYZ",
            "4J37-3L3S-C7RY",
            "FWK6-7K39-CLRT",
            "RA52-4G4U-CYR4",
            "D28F-3T8X-CQRQ",
            "CUSZ-BDYJ-AQZY",
            "5B5Z-5D6V-CZRY",
            "UCWX-BBYM-A8ZZ",
            "REYW-BAY3-A5ZZ",
            "CQZA-8F3Q-CYRZ",
            "C3C4-3SBP-YYCZ",
            "K9WQ-847M-CYRX",
            "ARWJ-8X8L-CGRY",
            "BEGA-55B3-YPCY",
            "3WKC-6Q79-CLRH",
            "N9XD-7R8M-CYRZ",
            "JV6H-7V3V-CJRX",
            "U7QJ-8X2F-CWR7",
            "GWSV-B9YL-AUZY",
            "426U-482W-CQRH",
            "MT2G-6U9R-CHRZ",
            "N9Z8-7M5Y-CZRZ",
            "WL9F-7T7Y-C9RA",
            "Y8XH-9V2M-CXRB",
            "NFNN-8D44-CZRZ",
            "YX6Z-BDYW-ALZC",
            "EBUN-7K8S-CYRZ",
            "PNB8-6M7C-C3RZ",
            "XVUK-BYYJ-BKYB",
            "JPXM-9N9E-CXRY",
            "VJNT-B7YD-A8Z9",
            "LBX9-7N4M-CZRY",
            "L5L7-5L4A-CURY",
            "N2US-863K-CRRZ",
            "U95E-5S8U-CXR7",
            "S5VG-8UAK-ZUC5",
            "V8Y9-8N2N-CXR8",
            "CZSN-9Q8Y-CZRA",
            "VNBC-7Q7C-C9RZ",
            "9GEW-7AB4-Y5CN",
            "U8GZ-8D96-CXR7",
            "3UVY-9C8L-CJRH",
            "F4C9-4NAT-ZSCY",
            "KFAL-6Z6Z-C3RY",
            "7U9S-764Z-CLRY",
            "J54B-3P9T-CTRW",
            "TM98-6M8Y-CAR7",
            "F9PR-756F-CYRS",
            "EG2P-537S-C4RS",
            "4VA4-4Z9J-CYRZ",
            "RXMU-B8YC-BMY5",
            "AZ2F-6T2R-CNRY",
            "LJQ8-7M4F-C8RZ",
            "TNWH-BVYL-ACZ7",
            "UL7G-7U4W-C9R8",
            "4ZYF-8T8N-CYRZ",
            "SR2F-7T2R-CFR6",
            "L23B-3P7S-CQRY",
            "HN98-5M7Y-CBRV",
            "CRJB-6P98-CGRQ",
            "QDKF-7T49-C2R4",
            "UYWB-BPYL-ANZ8",
            "T2LN-7B5R-C6RZ",
            "LPP4-7E5E-CZRY",
            "XP28-6M8R-CDRB",
            "MPJV-9979-CERZ",
            "VK8G-7U5X-C8R9",
            "MERC-7Q5G-C3RZ",
            "R38L-5Z9X-CRR4",
            "QQ8N-8Y3E-C4RZ",
            "M7WR-858M-CWRZ",
            "TMTL-BZYB-A7ZZ",
            "9RYQ-946G-CNRY",
            "DGSN-85AR-ZYCZ",
            "MFP6-6K8E-C4RZ",
            "RSRB-9P3G-CHR5",
            "R8L4-6AAX-Z4CZ",
            "VG4G-6U7T-C4R9",
            "HU4R-758U-CVRY",
            "NSNR-B5YD-AHZZ",
            "JHHY-8C77-C6RX",
            "S5UZ-9D8K-CUR5",
            "C7XA-6M2W-CPRY",
            "PH86-5K6X-C5R3",
            "W9KM-8A3Y-C9RZ",
            "VCFR-8555-C9RZ",
            "4MWS-866M-CBRY",
            "KLQF-8T2F-CARY",
            "GZGT-9766-CURY",
            "3ZEW-8A44-CHRY",
            "TYKS-B6YA-BNY7",
            "QPQ6-8K3F-CER4",
            "W2CJ-6X5R-C9RZ",
            "6Q2M-5S6E-CKRY",
            "MQGC-7Q65-CFRZ",
            "79AP-53BY-YKCY",
            "MG2N-6S34-CZRZ",
            "W372-4G4W-CRR9",
            "2G4K-4Y2T-C4RG",
            "QN73-5H9W-CBR4",
            "LFEM-7424-CZRY",
            "YT4U-987U-CHRC",
            "GCAK-5Y8Z-CURY",
            "QXQG-BUYF-AMZ4",
            "5YJL-7Z98-CNRJ",
            "DSYL-9Z6N-CHRR",
            "G4GZ-7DB6-YTCT",
            "C66S-562W-CURP",
            "YHE7-7L23-C6RC",
            "9PZR-956P-CERN",
            "D689-3N6X-CURQ",
            "VD4B-5P9T-C9RZ",
            "T4P4-6E2T-C6RZ",
            "46V7-4L8K-CVRH",
            "X954-5UBX-YACZ",
            "4QBE-5S5F-CYRZ",
            "7YLA-7A2N-CLRY",
            "C9NB-5P5C-CYRP",
            "Y2ES-7684-CRRB",
            "ZM9Z-BDYZ-AAZD",
            "HDSM-82AV-ZYCZ",
            "LBHQ-7457-CZRY",
            "YSQS-B6YG-BHYC",
            "534X-4B5U-CRRY",
            "F75R-554V-CVRS",
            "B8L8-4M8A-CXRY",
            "UACF-6T7Z-C7RZ",
            "TPRQ-B4YH-AEZ7",
            "CECE-5S23-CQRY",
            "RHXB-8P8M-C6R5",
            "XF8N-7Y93-CBRZ",
            "LNNX-BBYD-ACZZ",
            "WKDZ-BDY3-A9ZA",
            "N6D8-5MA2-ZVCZ",
            "UVP3-8H9E-CKR8",
            "TR7W-9A5X-CFR7",
            "6TEX-8B24-CKRY",
            "QWQT-B7YG-BLY4",
            "YG7R-854X-C4RC",
            "GFMY-8C7C-C4RU",
            "GRRU-B8YH-AGZU",
            "UUAK-9YAZ-Z8CZ",
            "5LA9-4N5Z-C9RJ",
            "G43N-4T6S-CTRY",
            "3ZRP-93AH-ZHCY",
            "763A-2S6U-CKRY",
            "Q3DH-5V92-CSR3",
            "FRJY-9C59-CGRT",
            "RBEC-6Q43-C5RZ",
            "SZS9-BNYH-A6ZZ",
            "C3YA-5N9S-CPRY",
            "43Z8-5MAS-ZHCY",
            "7BT8-5M5L-CYRZ",
            "FDFA-5432-CTRY",
            "UVK7-8L89-CKR8",
            "WUPL-BZYE-AJZA",
            "XY7F-8T9W-CMRB",
            "N2WQ-843M-CRRZ",
            "P6BZ-7D7V-C2RZ",
            "59PZ-7D4F-CYRY",
            "GKLX-9BAB-Z9CU",
            "8RKT-874A-CGRM",
            "FNDR-7583-CCRT",
            "FYXS-B6YN-BNYT",
            "P63M-5T6U-C2RZ",
            "PLQ2-7G4F-CAR3",
            "KJMH-7V8B-C8RY",
            "P3DA-52BS-Y2CZ",
            "YYWB-BPYL-BNYC",
            "2966-2K3V-CXRF",
            "2FEP-5364-C4RG",
            "U692-4G7Y-CUR7",
            "3Z3Z-7D6T-CNRH",
            "CUP7-7L4E-CJRQ",
            "V4CV-7982-CTR8",
            "JVYM-BKYX-AYZZ",
            "39CN-427Y-CGRY",
            "HY3J-7X3S-CMRV",
            "UXRR-B5YH-BMY8",
            "XA3T-775T-CYRA",
            "TMEF-8TA3-ZBC7",
            "AG8E-4S8X-C4RY",
            "8JUC-6Q9J-C8RM",
            "4Y2H-5V7R-CMRY",
            "W97U-788X-CXR9",
            "7J5F-4T6U-C7RL",
            "TSE6-7K73-CHR7",
            "G8FZ-7D45-CXRT",
            "ZP2R-859S-CDRD",
            "72RD-4R9G-CRRK",
            "BTKZ-9D5A-CPRY",
            "Q9BQ-742Y-C3RZ",
            "GEUS-868K-C3RU",
            "BCZD-7RBP-YYCZ",
            "VDUK-9Y4J-C2R9",
            "8BV4-5K4M-CYRZ",
            "XDEE-7S43-C2RB",
            "SFJ5-6J78-C4R6",
            "FQJK-8YA8-ZFCT",
            "F3WY-8C4M-CSRS",
            "SS34-6S3G-C6RZ",
            "5PKF-6T59-CERJ",
            "J2QS-765G-CRRW",
            "3453-BHYU-5SCG",
            "5FEF-4T93-C4RJ",
            "4LPJ-6X9E-CARY",
            "ZRK8-9MA9-ZGCD",
            "HB2G-4U6R-CZRU",
            "JZG2-7G25-CXRY",
            "L5R4-5G7U-CYRY",
            "P7AR-659W-C2RZ",
            "RRDJ-8X62-CGR5",
            "EY5J-7X2U-CMRS",
            "PKE5-6J43-C9R3",
            "UYPK-BYYE-ANZ8",
            "WD3N-7TBA-YZCZ",
            "6N7F-5TBW-YBCK",
            "WPPS-B6YF-BEYA",
            "UPT2-8G6E-C8RZ",
            "MTNH-9VBC-YZCZ",
            "78ZB-6PBX-YKCY",
            "YNWY-BCYM-2C3C",
            "7ULQ-844B-CJRL",
            "GSXX-BBYN-BHYU",
            "PKWM-9M99-C3RZ",
            "ASNR-858D-CHRY",
            "JT58-6MBU-YHCX",
            "NG76-5K2W-C4RZ",
            "UE7Z-8D6X-C2R8",
            "3G6Q-54BW-Y4CH",
            "QLN3-7H3C-CAR4",
            "B4ZE-6S4T-CYRZ",
            "JYXD-9R9M-CNRX",
            "XCCA-6B7Z-CZRA",
            "YL69-7NAV-Z9CC",
            "C7WB-6P2L-CWRP",
            "9PDN-73AE-ZNCY",
            "YPDD-8R52-CERC",
            "NETW-9A8J-C3RZ",
            "HAJL-6Z78-CZRU",
            "PHPZ-BDYF-A6Z3",
            "VX5E-8S3U-CLR9",
            "ZQUP-B3YK-BFYD",
            "X9UG-8U8J-CYRA",
            "3KBD-4R79-CHRY",
            "N8KJ-7XA9-ZXCZ",
            "VDZZ-BDYP-B2Y9",
            "38XS-762N-CXRG",
            "JHN6-6K5C-C6RX",
            "EXT4-8MAS-ZYCZ",
            "4P6R-652W-CDRY",
            "KP9Q-84AZ-ZDCY",
            "446W-4A6W-CSRH",
            "9FJA-5834-CNRY",
            "PUA3-6H8Z-C3RZ",
            "LXS3-8H5H-CMRZ",
            "DL5L-6ZAU-Z9CR",
            "TKT6-8K49-C7RZ",
            "29AN-4Y4F-CYRZ",
            "W2QG-7U6F-CRR9",
            "RYQR-B5YG-BNY5",
            "PADA-528Z-C2RZ",
            "MF8H-6V2X-C3RZ",
            "R838-4M6S-CWR4",
            "CRYW-BAYG-AQZY",
            "M6R8-6M3G-CVRZ",
            "J93N-5T4X-CWRY",
            "XVSY-BCYK-2B6Z",
            "YDCJ-7X82-CCRZ",
            "FK2Y-7CBS-Y8CT",
            "RHBA-6655-CZRZ",
            "VKXZ-BDYN-B9Y9",
            "LQAV-898F-CZRY",
            "GMSS-964B-CURY",
            "HD6M-5W8V-CYRZ",
            "RXNF-9T8C-CMR5",
            "AABY-6C5Z-CNRY",
            "MDFP-7355-C2RZ",
            "H9W8-6M6L-CYRU",
            "S4SC-7Q2H-CTR5",
            "STTT-B7YJ-B6YZ",
            "MN26-5K3R-CBRZ",
            "UU6M-8W88-CZRZ",
            "PV8C-7Q6X-CJR3",
            "N7SW-9AAW-ZZCZ",
            "GA7X-6B6X-CYRT",
            "UJHH-8V36-C8R8",
            "LMPJ-8X7E-CBRZ",
            "WBYA-8N7A-CZRZ",
            "59VY-7C9L-CYRY",
            "TJM3-7H3B-C8R7",
            "U72J-5X8R-CVR7",
            "LXBF-8TAM-ZZCY",
            "ADES-6654-C2RY",
            "WGBK-7Y95-CARZ",
            "EWSC-8Q6H-CLRS",
            "EC6V-693W-CSRY",
            "AJSD-7RAH-Z8CY",
            "SYUZ-BDYK-2N76",
            "LN2K-6Y6R-CBRZ",
            "F93P-532T-CXRS",
            "XV4Q-944U-CJRB",
            "48KX-6B5A-CXRH",
            "K464-3V4S-CXRY",
            "PUWK-BYYL-AJZ3",
            "BCUH-7VAJ-ZPCY",
            "DXDB-7PA2-ZMCR",
            "XEE4-6353-CBRZ",
            "77XT-776N-CWRK",
            "CG9B-4P8Y-C4RQ",
            "JDLM-7B52-CXRY",
            "5VMQ-844C-CKRJ",
            "RBX5-7J6M-C5RZ",
            "LDYV-9992-CZRY",
            "AT6K-6Y5V-CHRY",
            "EHYB-7P6N-C6RS",
            "7GC8-4M35-CLRY",
            "HLAP-733A-CVRY",
            "ZAMH-8V4B-CZRC",
            "KFNC-7QAC-Z4CY",
            "VMMZ-BDYC-BBY9",
            "QSTD-9R6H-C4RZ",
            "GLQZ-9D8G-CARU",
            "GB4S-569U-CZRT",
            "HRTH-9VAG-ZVCY",
            "3H2A-3R25-CHRY",
            "UMDY-9C93-CBR8",
            "TY84-7X5M-C7RZ",
            "Y623-4H5R-CURB",
            "MEJS-8639-C3RZ",
            "V3FH-6V64-CSR8",
            "7ADP-5353-CZRK",
            "B8V6-5K6K-CXRY",
            "VTLM-BBY9-AZZZ",
            "UKEX-9B74-C9R8",
            "JYC8-7M3N-CXRY",
            "BSR4-7GAH-ZPCY",
            "QHM2-6G7B-C6R4",
            "PKY7-8L6N-C9R3",
            "UML3-7H6A-CBR8",
            "TCUP-936K-C7RZ",
            "Q98Y-7C7Y-CXR3",
            "57ZJ-6X6W-CYRZ",
            "BN78-4M9W-CBRP",
            "A76N-4W6V-CNRY",
            "LPVH-9V4K-CERZ",
            "VT2H-7V9R-CHR9",
            "J27P-533X-CQRW",
            "KYU3-8H7J-CNRY",
            "Q69M-6Z3U-C3RZ",
            "D5PY-7C7F-CURQ",
            "BWKG-7U99-CLRP",
            "6DG6-4KB5-Y2CK",
            "N363-3H5V-CRRZ",
            "974B-3PBT-YVCM",
            "FPWP-937M-CERT",
            "H4EA-435T-CURY",
            "NU93-6H5Y-CZRZ",
            "LN8S-86AY-ZBCZ",
            "MJ6T-776W-C7RZ",
            "UR57-6L9U-CFR8",
            "TRYY-BCYG-274Z",
            "UXWJ-BXYL-BMY8",
            "AYXJ-9X6M-CNRY",
            "NPL9-7N8A-CERZ",
            "K5JQ-74A9-ZUCX",
            "HCS4-6HBV-YYCZ",
            "RCHC-6Q86-C5RZ",
            "FKUJ-8X4J-C9RT",
            "QFSY-BCY4-A4ZZ",
            "2JH9-4N76-C8RG",
            "39SH-5V7H-CYRG",
            "F7RE-6S3G-CWRS",
            "Y6SA-7H8V-CBRZ",
            "8323-BHYR-6RCL",
            "FXBA-6M9T-CYRZ",
            "S8PY-9C5F-CXR5",
            "BV4B-5P7T-CJRP",
            "9KNY-8C6D-C9RN",
            "FPJK-7Y98-CERT",
            "2ZBM-7GAY-ZZCA",
            "DBHM-673R-CYRZ",
            "CB23-2H8R-CZRP",
            "YTAG-8U9Z-CHRC",
            "2A3N-3T8Y-CFRY",
            "74GP-5326-CTRK",
            "9UZF-8T9J-CNRY",
            "YQ4J-8X3T-CERC",
            "ZPEE-8S83-CERD",
            "8RSM-8G5M-CYRZ",
            "BJDS-76B3-Y8CP",
            "39X7-5L2M-CYRG",
            "HVSA-8H6K-CVRY",
            "L38Z-6D7Y-CRRY",
            "57T8-4M9W-CYRZ",
            "RCW3-7H4L-C5RZ",
            "JGLD-6R9A-C5RX",
            "ELMZ-9D2C-CARS",
            "6ALN-6BAZ-ZJCY",
            "YCMD-8RBB-YCCZ",
            "LJHG-7U36-C8RZ",
            "PG3H-6VBS-Y4C3",
            "V96N-6W9X-C8RZ",
            "ZMHA-864B-CDRZ",
            "QJFS-8685-C8R4",
            "7PJL-7Z28-CERL",
            "4Z6B-5P6V-CNRY",
            "GXPR-B5YF-AMZU",
            "MLES-8654-CARZ",
            "7YBD-6R5N-CLRY",
            "66VW-7A5L-CVRJ",
            "CW9D-6R6Y-CKRQ",
            "YEDX-9B43-C3RC",
            "474W-4A7U-CVRH",
            "RPWG-BUYL-AEZ5",
            "RXUN-BKYM-B5YZ",
            "WGKE-8S29-C5RA",
            "RNQR-B5YG-ACZ5",
            "Y9M8-7M3B-CYRB",
            "WSE4-738H-CARZ",
            "LFSJ-8X3H-C4RZ",
            "GXRJ-9X5G-CMRU",
            "CPJZ-9DB9-YECQ",
            "LVYN-BKYZ-AYZZ",
            "YUD5-8J22-CJRC",
            "47QE-5SBF-YWCH",
            "4L29-3N6R-C9RY",
            "LZ5C-7Q3U-CNRZ",
            "QJA3-5H8Z-C7R4",
            "5U4M-6UBJ-YYCZ",
            "EKSX-9B59-CSRY",
            "XEGB-7P45-C3RB",
            "7SVV-997L-CHRL",
            "AFJK-6Y48-C4RY",
            "T9J8-6M58-CYR6",
            "MWWQ-B4YM-BLYZ",
            "AS83-4H9X-CGRY",
            "8SEQ-7464-CHRM",
            "HAZC-7Q4Z-CURY",
            "PFQ6-7K2F-C4R3",
            "FXUS-B6YK-AMZT",
            "FKD2-5GA2-Z9CT",
            "STDQ-9463-C6RZ",
            "88VC-5Q9K-CXRL",
            "TEBY-8C83-C7RZ",
            "FAPN-7F3Z-CSRY",
            "MCMF-7TBB-YZCZ",
            "XAJV-9939-CZRA",
            "GKXD-8R2M-C9RU",
            "TDHW-9AB7-Y2C7",
            "N5MA-6BAU-ZZCZ",
            "6P38-4M2S-CDRK",
            "N8W5-6J8L-CXRZ",
            "WBXZ-BDYN-BAYZ",
            "DYAR-854N-CRRY",
            "8KPH-7VAE-Z9CM",
            "9YHP-8357-CNRN",
            "QXDJ-9XB2-YMC4",
            "KYQW-BAYG-BNYY",
            "W3CL-6Z8S-C9RZ",
            "6MPC-6Q5E-CBRK",
            "4KHU-78B7-Y9CY",
            "DPWR-957M-CERR"
        );
        
        foreach($keys as $key){
            $ipr2 = new \App\Ipr2();
            $ipr2->key = $key;
            $ipr2->save();
        }
    });
});
