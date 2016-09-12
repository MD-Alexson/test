<footer>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                @if(!empty($project->vk) || !empty($project->fb) || !empty($project->tw) || !empty($project->yt) || !empty($project->insta) || !empty($project->blog))
                <ul class="list-inline text-center">
                    @if(strlen($project->vk))
                    <li>
                        <a href="{{ prep_url($project->vk) }}" target="_blank">
                            <span class="fa-stack fa-lg">
                                <i class="fa fa-circle fa-stack-2x"></i>
                                <i class="fa fa-vk fa-stack-1x fa-inverse"></i>
                            </span>
                        </a>
                    </li>
                    @endif
                    @if(strlen($project->fb))
                    <li>
                        <a href="{{ prep_url($project->fb) }}" target="_blank">
                            <span class="fa-stack fa-lg">
                                <i class="fa fa-circle fa-stack-2x"></i>
                                <i class="fa fa-facebook fa-stack-1x fa-inverse"></i>
                            </span>
                        </a>
                    </li>
                    @endif
                    @if(strlen($project->tw))
                    <li>
                        <a href="{{ prep_url($project->tw) }}" target="_blank">
                            <span class="fa-stack fa-lg">
                                <i class="fa fa-circle fa-stack-2x"></i>
                                <i class="fa fa-twitter fa-stack-1x fa-inverse"></i>
                            </span>
                        </a>
                    </li>
                    @endif
                    @if(strlen($project->yt))
                    <li>
                        <a href="{{ prep_url($project->yt) }}" target="_blank">
                            <span class="fa-stack fa-lg">
                                <i class="fa fa-circle fa-stack-2x"></i>
                                <i class="fa fa-youtube fa-stack-1x fa-inverse"></i>
                            </span>
                        </a>
                    </li>
                    @endif
                    @if(strlen($project->insta))
                    <li>
                        <a href="{{ prep_url($project->insta) }}" target="_blank">
                            <span class="fa-stack fa-lg">
                                <i class="fa fa-circle fa-stack-2x"></i>
                                <i class="fa fa-instagram fa-stack-1x fa-inverse"></i>
                            </span>
                        </a>
                    </li>
                    @endif
                    @if(strlen($project->blog))
                    <li>
                        <a href="{{ prep_url($project->blog) }}" target="_blank">
                            <span class="fa-stack fa-lg">
                                <i class="fa fa-circle fa-stack-2x"></i>
                                <i class="fa fa-rss fa-stack-1x fa-inverse"></i>
                            </span>
                        </a>
                    </li>
                    @endif
                </ul>
                <br/>
                @endif
                @if(strlen($project->custom_copyright))
                <p class="copyright text-center text-muted"><?php echo nl2br($project->custom_copyright); ?></p>
                @endif
                @if(!$project->disable_copyright)
                <br/>
                <div class="text-center">
                    <a href='{{ config('app.url') }}' target="_blank">
                        <img src='{{ asset('assets/images/logo_header.png') }}'>
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</footer>
<script type="text/javascript">
    $(document).ready(function(){
        $(window).resize(function(){
            footer();
        });
        $(document).ready(function(){
            footer();
        });
    });
</script>
@include('frontend.inc.popups')