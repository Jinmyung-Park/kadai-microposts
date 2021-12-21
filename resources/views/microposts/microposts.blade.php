@if (count($microposts) > 0)
    <ul class="list-unstyled">
        @foreach ($microposts as $micropost)
            <li class="media mb-3 border">
                {{-- 投稿の所有者のメールアドレスをもとにGravatarを取得して表示 --}}
                <img class="mr-2 rounded pt-1" src="{{ Gravatar::get($micropost->user->email, ['size' => 50]) }}" alt="">
                <div class="media-body">
                    <div>
                        {{-- 投稿の所有者のユーザ詳細ページへのリンク --}}
                        {!! link_to_route('users.show', $micropost->user->name, ['user' => $micropost->user->id]) !!}
                        <span class="text-muted">posted at {{ $micropost->created_at }}</span>
                    </div>
                    <div>
                        {{-- 投稿内容 --}}
                        <p class="mb-0  border-bottom ">{!! nl2br(e($micropost->content)) !!}</p>
                    </div>
                    <div class="row">
                        <div class="col-sm-2 mt-2 mb-1">
                        @if (Auth::user()-> is_favorited($micropost->id))
                            {{-- アンフォローボタンのフォーム --}}
                            {!! Form::open(['route' => ['user.deleteFavorite', $micropost->id], 'method' => 'delete']) !!}
                            {!! Form::submit('Unfavorite', ['class' => "btn btn-secondary rounded-circle btn-sm "]) !!}
                            {!! Form::close() !!}
                        @else
                            {!! Form::open(['route' => ['user.favorite', $micropost->id], 'method' => 'post']) !!}
                            {!! Form::submit('Favorite', ['class' => "btn btn-info rounded-circle btn-sm"]) !!}
                            {!! Form::close() !!}
                        @endif
                        </div>
                        <div class="col-sm-2 mt-2 mb-1">  
                        @if (Auth::id() == $micropost -> user_id)
                            {!! Form::open(['route' => ['microposts.destroy', $micropost->id], 'method' => 'delete']) !!}
                            {!! Form::submit('Delete',['class' => 'btn btn-danger btn-sm ']) !!}
                            {!! Form::close() !!}
                        @endif
                        </div>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
    {{-- ページネーションのリンク --}}
    {{ $microposts->links() }}
@endif
