<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{

    public function index(){

        $search = request('search');
        $user = auth()->user();

        if($search) {

            $videos = Video::where([
                ['title', 'like', '%'.$search.'%']
            ])->get();


        } else {
            $videos = Video::with('user')->get();
            $posts = Post::with('user')->get();

        }

        if($user){
            $channels = Subscription::where('user_id', $user->id)->get();
            $subscriptions = [];

            for ($i=0; $i < count($channels); $i++) {
                $channel =  User::select('name', 'id', 'profile_photo_path')->where('id', $channels[$i]->channel_id)->get();
                $subscriptions[$i] = $channel[0]->getAttributes();

            }
        } else {
            $subscriptions = [];
        }



        return view('youtube.home', [
            'videos' => $videos,
            'subscriptions' => $subscriptions,
            'posts' => $posts,
            'search' => $search,
            'user' => $user
            ]);
    }

    public function create(){
        return view('studio.create');
    }

    public function store(Request $request){


        $post = new Post;

        $post->text          = $request->text;
        //$video->desc           = $request->desc;

        //if($request->hasFile('video') && $request->file('video')->isValid()){

            // $requestVideo = $request->video;
            // //dd($requestVideo);
            // $extension = substr($requestVideo->getClientOriginalName(), -3);

            // $videoName = md5($requestVideo->getClientOriginalName() . strtotime("now")) . "." . $extension ;

            // $requestVideo->move(public_path('/videos/video'), $videoName);

            // $video->video = $videoName;

            // $getID3 = new \getID3;
            // $file = $getID3->analyze(public_path('/videos/video/'. $videoName));

            // $duration = $file['playtime_string'];

            // $video->video_duration = $duration;

        //}

        if($request->hasFile('thumb') && $request->file('thumb')->isValid()){
            $requestThumb = $request->thumb;

            $extension = $requestThumb->extension();

            $thumbName = md5($requestThumb->getClientOriginalName() . strtotime("now")) . "." . $extension ;

            $requestThumb->move(public_path('/videos/img'), $thumbName);

            $video->thumb = $thumbName;

        }

        $user = auth()->user();
        $post->user_id = $user->id;


        //dd($post);
        $post->save();



        return redirect('/')->with('msg', 'Post criado com sucesso !');


    }

    public function show($id){
        $video = Video::findOrFail($id);


        $user = auth()->user();

        if($user){
            $visualization = Visualization::where([['video_id',$id],['user_id',$user->id]])->get()->count();
            $user_id = $user->id;


            $playlists = Playlist::where('user_id',$user->id)->get();
            $channels = Subscription::where('user_id', $user->id)->get();

            for ($i=0; $i < count($channels); $i++) {
                $channel =  User::select('name', 'id', 'profile_photo_path')->where('id', $channels[$i]->channel_id)->get();
                $subscriptions[$i] = $channel[0]->getAttributes();

            }

        } else {
            $user_id = -1;
            $playlists = [];
            $subscriptions = [];
        }

        $data = $video->getAttributes();

        if($user && $visualization == 0) {

            unset($data['id']);

            $data['totalVisualizations'] = Visualization::where('video_id',$id)->get()->count();
            Video::findOrFail($id)->update($data);

            $visualization = new Visualization;
            $visualization->user_id = $user->id;
            $visualization->video_id = $video->id;
            $visualization->save();

            $historic = new Historic;
            $historic->user_id = $user->id;
            $historic->video_id = $video->id;
            $historic->save();

        }


        unset($data['id']);

        $visualizations = Visualization::where('video_id',$id)->count();
        $comments       = Comment::where('video_id',$id)->count();

        $data['totalVisualizations'] = $visualizations;
        $data['totalComments']       = $comments;
        Video::findOrFail($id)->update($data);

        $videoOwner = User::where('id', $video->user_id)->first()->toArray();



        $comments = DB::table('users')
        ->join('comments', 'users.id', '=', 'comments.user_id')
        ->join('videos', 'videos.id', '=', 'comments.video_id')
        ->select('users.*', 'comments.*')
        ->distinct()
        ->get();

        //dd($comments);

        $likes = Like::where('video_id',$id)->get()->count();
        $deslikes = Deslike::where('video_id',$id)->get()->count();
        $videos = Video::take(10)->get();
        $tags = Tag::get();

        /*********** */

        $subs = Subscription::where('channel_id',$video->user_id)->get()->count();
        $subscriptions = Subscription::get();


        /**************** */

        return view('youtube.video', [
            'video' => $video,
            'playlists' => $playlists ,
            'videoOwner' => $videoOwner,
            'subscriptions' => $subscriptions,
            'comments' => $comments,
            'likes' => $likes,
            'deslikes' => $deslikes,
            'videos' => $videos,
            'subs' => $subs,
            'user' => $user,
            'tags' => $tags,
            'user_id' => $user_id ]);

    }

    public function dashboard() {

        $user = auth()->user();

        $videos = $user->videos;

        $eventsAsParticipant = $user->eventsAsParticipant;

        return view('videos.dashboard',
            ['videos' => $videos, 'eventsasparticipant' => $eventsAsParticipant]
        );

    }

    public function destroy($id){

        Video::findOrFail($id)->delete();

        return redirect('/dahsboard')->with('msg', 'Evento excluído com sucesso!');

    }

    public function edit($id){

        $user = auth()->user();

        $video = Video::findOrFail($id);

        if($user->id != $video->user_id) {
            return redirect('/dashboard');

        }

        return view('videos.edit', ['video' => $video]);

    }

    public function update(Request $request){
        $data = $request->all();

        // Image Upload
        if($request->hasFile('image') && $request->file('image')->isValid()) {

            $requestImage = $request->image;

            $extension = $requestImage->extension();

            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;

            $requestImage->move(public_path('img/videos'), $imageName);

            $data['image'] = $imageName;

        }

        Video::findOrFail($request->id)->update($data);

        return redirect('/dashboard')->with('msg', 'Video editado com sucesso!');

    }


}
