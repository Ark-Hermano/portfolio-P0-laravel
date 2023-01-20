
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

    <div class="body" >
        <div class="sidebar">
            <div class="tweet-icon"></div>
            <div class="home-icon"></div>
            <div class="search-icon"></div>
            <div class="notification-icon"></div>
            <div class="message-icon"></div>
            <div class="profile-icon"></div>
            <div class="more-icon"></div>
        </div>
        <div class="feed">
            <div class="header">
                <div>Home</div>
                <div class="top-tweets"></div>
            </div>
            <div class="user-post-form">
                <div>
                    <div class="user-pic">

                    </div>
                </div>
                <div>
                    <div class="post-text">
                        <form action="/post/store" method="Post">
                            @csrf
                            <input type="text" name="text" placeholder="What's happening"/>
                            <button type="submit'" >send</button>
                        </form>

                    </div>
                    <div class="post-actions">
                        <div class="add-media"></div>
                        <div class="add-gif"></div>
                        <div class="add-emoji"></div>
                        <div class="send"></div>
                    </div>
                </div>

            </div>
            @foreach ($posts as $post)
                <div class="post">
                    <div>
                        <div class="user-pic">

                        </div>
                    </div>
                    <div>
                        <div class="user-data">
                            <div class="username"></div>
                            <div class="user-surname"></div>
                            <div class="options"></div>
                        </div>
                        <div class="post-text">
                           {{$post->text}}
                        </div>
                        <div class="post-actions">
                            <div class="comment"></div>
                            <div class="retweet"></div>
                            <div class="like"></div>
                            <div class="share"></div>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="post">
                <div>
                    <div class="user-pic">

                    </div>
                </div>
                <div>
                    <div class="user-data">
                        <div class="username"></div>
                        <div class="user-surname"></div>
                        <div class="options"></div>
                    </div>
                    <div class="post-text">
                        Back4blood parece legal, to pronto para fazer que nem fiz com left4dead, jogar 50 com 12 amigos diferente ate nao suportar ver alguem falar o nome do game.
                    </div>
                    <div class="post-actions">
                        <div class="comment"></div>
                        <div class="retweet"></div>
                        <div class="like"></div>
                        <div class="share"></div>
                    </div>
                </div>
            </div>

        </div>
        <div class="aside">
            <div class="search-bar"></div>
            <div class="whats-happening"></div>
        </div>
    </div>


</body>
</html>
