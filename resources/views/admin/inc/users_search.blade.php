<div class="project-search">
    <form action="{{ action('Admin\UsersController@search') }}">
        <fieldset>
            <input name="query" type="text" class="input" placeholder="Найти пользователя" required="required" maxlength="40" style="background-image: none;">
            <button class="loupe"></button>
        </fieldset>
    </form>
</div>