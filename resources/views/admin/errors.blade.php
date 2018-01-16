@if($errors->any())
    <div class="conteiner">
        <div class="row">
            @foreach($errors->all() as $error)
                {{$error}}
            @endforeach
        </div>
    </div>
@endif