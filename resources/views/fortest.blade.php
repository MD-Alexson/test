<form action="/api/payment/eautopay" method="post">
    <input type="hidden" name="id" value="42323">
    <input type="hidden" name="first_name" value="dd">
    <input type="hidden" name="email" value="md.alexson@gmail.com">
    <input type="hidden" name="phone" value="777">
    <input type="hidden" name="product_id" value="153868">
    <input type="hidden" name="status" value="5">
    <input type="hidden" name="hash" value="{{ md5("42323md.alexson@gmail.com77762256225") }}">
    <input type="submit">
</form>