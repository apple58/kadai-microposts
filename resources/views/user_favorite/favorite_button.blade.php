@if (Auth::user()->is_favorites($micropost->id))
    {!! Form::open(['route' => ['user.unfavorites', $micropost->id], 'method' => 'delete']) !!}
        {!! Form::submit('Unfavorite', ['class' => "btn btn-success btn-block"]) !!}
    {!! Form::close() !!}
@else
    {!! Form::open(['route' => ['user.favorites', $micropost->id], 'method' => 'post']) !!}
        {!! Form::submit('Favorite', ['class' => "btn btn-default btn-block"]) !!}
    {!! Form::close() !!}
@endif