<div id='post-content'>
    <?php echo html_entity_decode($post->post_html); ?>
    <br/>
    <?php echo html_entity_decode($post->embed); ?>
    @if($post->videos()->count())
    <link href="http://vjs.zencdn.net/5.0/video-js.min.css" rel="stylesheet">
    <script src="http://vjs.zencdn.net/5.0/video.min.js"></script>
    @foreach($post->videos as $video)
    <hr>
    <br>
    <video class="video-js vjs-default-skin" controls preload="auto" data-setup='{}'>
        <source src="{{ pathTo($video->path, 'filepath') }}" type='video/mp4'>
        <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
    </video>
    <script type='text/javascript'>
        $(document).ready(function(){
            $(window).load(function(){
                @if(Session::get('guard') === 'frontend')
                    var rand = "{{ Auth::guard('frontend')->user()->rand }}";
                @else
                    var rand = "";
                @endif
                $('div.video-js').append("<p class='wat wat1'>"+rand+"</p>");
                $('div.video-js').append("<p class='wat wat2'>"+rand+"</p>");
            });
        });
    </script>
    @endforeach
    @endif
    @if($post->files()->count())
    <hr>
    <br/>
    <h3>Прикрепленный материал:</h3>
    <br/>
    <ul class="files">
        @foreach($post->files as $file)
        <li>
            <a href='{{ pathTo($file->path, 'filepath') }}'><img src="{{ asset('assets/images/files/'.$file->type.'.png') }}" class="img-thumbnail"> {{ $file->name }}</a>
        </li>
        @endforeach
    </ul>
    @endif
    @if($post->homework_enabled)
    <hr/>
    <br/>
    <h3>Домашнее задание:</h3>
    <?php echo html_entity_decode($post->homework); ?>
        @if(Session::get('guard') === 'frontend')
            @if(Auth::guard('frontend')->user()->homeworks()->where('post_id', $post->id)->count())
            <?php $homework = Auth::guard('frontend')->user()->homeworks()->where('post_id', $post->id)->first(); ?>
            <form method="post" action="{{ action("Frontend\PostsController@homeworkUpdate", ['domain' => $project->domain, 'post_id' => $post->id, 'homework_id' => $homework->id]) }}" class="post_form" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="row control-group">
                    <div class="form-group col-xs-12 floating-label-form-group controls">
                        <label>Домашнее задание</label>
                        <textarea name="text" class="form-control" placeholder="Текст домашнего задания" required="required" maxlength="1024" rows="6">{{ $homework->text }}</textarea>
                        <p class="help-block text-danger"></p>

                        @if(!empty($homework->file_path))
                        Вы прикрепили файл: <a href="{{ pathTo($homework->file_path, 'filepath') }}">{{ $homework->file_name }}</a><br/><br/>
                        @endif
                        <p>
                            <input type="file" name="file">
                            <input type="hidden" name="MAX_FILE_SIZE" value="52428800" />
                            Максимальный размер файла - 50мб, тип - zip, rar, 7z, pdf, doc, docx, ppt, pptx, xls, xlsx, png, jpg, jpeg, gif, mpga, txt, mp3, mp4, avi, psd, ai, cdr или tiff
                        </p>

                        <button type="submit" class="btn btn-default btn-success">Обновить</button>
                        <a href="/posts/{{ $post->id }}/homeworks/{{ $homework->id }}/destroy" class="btn btn-default btn-danger">Удалить</a>
                        @if($homework->checked)
                        <p style="color: #72c01d">Ваше домашнее задание было проверено и принято!</p>
                        @else
                        <p>Ваше домашнее задание проверяется</p>
                        @endif
                    </div>
                </div>
            </form>
            @else
            <form method="post" action="{{ action("Frontend\PostsController@homework", ['domain' => $project->domain, 'post_id' => $post->id]) }}" class="post_form" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="row control-group">
                    <div class="form-group col-xs-12 floating-label-form-group controls">
                        <label>Домашнее задание</label>
                        <textarea name="text" class="form-control" placeholder="Текст домашнего задания" required="required" maxlength="1024" rows="6"></textarea>
                        <p>
                            <input type="file" name="file" value="Прикрепить файл">
                            <input type="hidden" name="MAX_FILE_SIZE" value="52428800" />
                            Максимальный размер файла - 50мб, тип - zip, rar, 7z, pdf, doc, docx, ppt, pptx, xls, xlsx, png, jpg, jpeg, gif, mpga, txt, mp3, mp4, avi, psd, ai, cdr или tiff
                        </p>
                        <p class="help-block text-danger"></p>
                        <button type="submit" class="btn btn-default btn-success">Подтвердить выполнение</button>
                    </div>
                </div>
            </form>
            @endif
        @endif
    @endif
    @if($post->comments_enabled)
    <hr>
    <br/>
    <h3>Комментарии:</h3>
    <br/>
    @foreach($post->comments()->where('allowed', true)->orderBy('created_at', 'ASC')->get() as $comment)
        @if($comment->commentable_type === 'App\Suser')
        <?php $user = \App\Suser::findOrFail($comment->commentable_id); ?>
        @else
        <?php $user = \App\User::findOrFail($comment->commentable_id); ?>
        @endif

        @if($comment->commentable_type === 'App\User')
        <?php $type = "<span class='admin'>(Администратор)</span>"; ?>
        @elseif($user->id === Auth::guard(Session::get('guard'))->id())
        <?php $type = "<span class='user'>(Вы)</span>"; ?>
        @else
        <?php $type = ""; ?>
        @endif

        <?php $destroy = ""; ?>

        @if(Session::get('guard') === 'backend' && $project->user->id === Auth::guard('backend')->id())
        <?php $destroy = " <a href='/posts/".$post->id."/comments/".$comment->id."/destroy' class='destroy'>&times;</a>"; ?>
        @elseif($user->id === Auth::guard(Session::get('guard'))->id())
        <?php $destroy = " <a href='/posts/".$post->id."/comments/".$comment->id."/destroy' class='destroy'>&times;</a>"; ?>
        @endif
        <div class='comment'>
            <h4>{{ $user->name }} <?php echo $type; ?> [<span class='toLocalTime'>{{ $comment->created_at }}</span>] <?php echo $destroy; ?></h4>
            <p><?php echo nl2br($comment->text); ?></p>
        </div>
    @endforeach
    <form method="post" action="{{ action("Frontend\PostsController@comment", ['domain' => $project->domain, 'post_id' => $post->id]) }}" class="post_form">
        {{ csrf_field() }}
        <div class="row control-group">
            <div class="form-group col-xs-12 floating-label-form-group controls">
                <label>Комментарий</label>
                <textarea name="text" class="form-control" placeholder="Текст комментария" required="required" maxlength="1024" rows="6"></textarea>
                <p class="help-block text-danger"></p>
                <button type="submit" class="btn btn-default btn-success">Добавить комментарий</button>
            </div>
        </div>
    </form>
    @endif
    <br/>
</div>