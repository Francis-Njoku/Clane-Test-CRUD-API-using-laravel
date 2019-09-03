<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use App\Article;
use App\Rating;
use App\Http\Resources\Post as PostResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use \Illuminate\Http\Response as Res;
use Response;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $client = DB::table('users')
            ->join('article', 'users.id', '=', 'article.author')

            ->select('article.id as id', 'users.name as author', 'article.title as title', 'article.post as post',
                'article.created_at as created_at', 'article.updated_at as updated_at')
            ->paginate();
        if(count($client) >= 1)
        {
            return PostResource::collection($client);
        }
        else{
            return $this->check_post_exist('2');
        }
    }
    /**
     * Check if user is logged in
     * @Return \Illuminate\Http\Response
     *
     */
    private function check_user_logged_in()
    {
        if (!Auth::check()) {
            return [
                'message'          => 'Author not logged in',
                'status'         =>  'Failed',
            ];
        }
    }

    private function check_post_exist($id)
    {
        if ($id == 1)
        {
            return  Response::json([
                'message'          => 'Post does not exist',
                'status_code' => Res::HTTP_NOT_FOUND,
                'status'         =>  'Failed',
            ]);
        }
        elseif($id == 2)
        {
            return  Response::json([
                'message'          => 'No post available',
                'status_code' => Res::HTTP_NOT_FOUND,
                'status'         =>  'Failed',
            ]);
        }
        elseif($id == 3)
        {
            return  Response::json([
                'message'          => 'No search result',
                'status_code' => Res::HTTP_NOT_FOUND,
                'status'         =>  'Failed',
            ]);
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_author_name()
    {
        if (Auth::check()) {
            // The user is logged in...
        }
        $user_type = DB::table('users')->where('id', auth()->user()->id)->first();
        abort_if(!$user_type->approve == '1', 404);
    }
    public function create()
    {
        if (!Auth::check()) {
        return Response::json([
            'message'          => 'Unauthenticated',
            'status_code' => Res::HTTP_UNAUTHORIZED,
            'status'         =>  'Failed',
        ]);
    }

        return view('posts.create2');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Check if author is logged in
        if (!Auth::check()) {
            return Response::json([
                'message'          => 'Unauthenticated',
                'status_code' => Res::HTTP_UNAUTHORIZED,
                'status'         =>  'Failed',
            ]);
        }

        // Sanitize the input by stripping away the script tags using
        $clean_post = strip_tags($request->input('post'));

        // Validate request
        $this->validate($request, [
            //'title' => 'required|max:255|regex:[A-Za-z1-9 ]',
            'title' => 'required|max:255',

            //'post' => $clean_post,
            'post' => 'required'
        ]);

        // Store Resource
        $post = new Article();
        $post->title = $request->input('title');
        $post->post = $request->input('post');
        $post->author = auth()->user()->id;
        $post->status = 'ápproved';
        $post->save();
        return [
            'status' => 'success',
            'status_code' => Res::HTTP_CREATED,
            'message' => 'Post created',
        ];


        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = DB::table('users')
            ->join('article', 'users.id', '=', 'article.author')

            ->select('article.id as id', 'users.name as author', 'article.title as title', 'article.post as post',
                'article.created_at as created_at', 'article.updated_at as updated_at')
            ->where('article.id', $id)
            ->get();
        if(count($client) >= 1)
        {
            return PostResource::collection($client);
        }
        else{
            return $this->check_post_exist('1');
        }

    }

    /**
     * Error if update not from author
     */
    private function check_if_author($id)
    {
        // Check for correct user
        if(auth()->user()->id !== $id)
        {
            return redirect('/article')->with('error', 'Unauthorized Page');

        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $posts = Article::find($id);

        // Check for correct user
        if(auth()->user()->id !== $posts->author)
        {
            return redirect('/article')->with('error', 'Unauthorized Page');

        }
        return view('article.edit')->with('posts', $posts);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Check if author is logged in
        if (!Auth::check()) {
            return Response::json([
                'message'          => 'Author not logged in',
                'status'         =>  'Failed',
            ]);
        }

        // Sanitize the input by stripping away the script tags using
        $clean_post = strip_tags($request->input('post'));

        // Validate request
        $this->validate($request, [
            //'title' => 'required|max:255|regex:[A-Za-z1-9 ]',

            'title' => 'required|max:255|regex:[A-Za-z1-9 ]',

            //'post' => $clean_post,
            'post' => 'required',
            'author' => 'required|between:0, 100000000',
        ]);

        if(auth()->user()->id !== $request->author)
        {
            return Response::json([
                'message'          => 'You are not the author ',
                'status_code' => Res::HTTP_UNAUTHORIZED,
                'status'         =>  'Failed',
            ]);

        }

        // Store Resource
        $post = Article::find($id);
        $post->title = $request->input('title');
        $post->post = $request->input('post');
        $post->author = auth()->user()->id;
        $post->status = 'ápproved';
        $post->save();
        return [
            'status' => 'success',
            'status_code' => Res::HTTP_CREATED,
            'message' => 'Post created',
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Article::find($id);
        // Check for correct user
        if(auth()->user()->id !== $post->user_id)
        {
            return Response::json([
                'message'          => 'You are not the author ',
                'status_code' => Res::HTTP_UNAUTHORIZED,
                'status'         =>  'Failed',
            ]);
        }

        $post->delete();
        return [
            'status' => 'success',
            'status_code' => Res::HTTP_OK,
            'message' => 'Post deleted',
        ];
    }

    public function respond($data, $headers = []){
        return Response::json($data, $this->getStatusCode(), $headers);
    }

    /**
     * @var int
     */
    protected $statusCode = Res::HTTP_OK;
    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function rating(Request $request)
    {

        // Validate request
        $this->validate($request, [
            'post_id' => 'required|between:1, 5',
            'rate' => 'required|between:0, 100000000',

        ]);

        // Store Resource
        $post = new Rating();
        $post->post_id = $request->input('post_id');
        $post->rate = $request->input('rate');
        $post->save();
        return [
            'status' => 'success',
            'status_code' => Res::HTTP_OK,
            'message' => 'Rating successful',
        ];

    }

    public function show2($id)
    {
        $posts = Article::find($id);
        return view('posts.show2')->with('posts', $posts);
    }

    /**
     * Get search results
     */
    public function search(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
        ]);
        if($request->get('title'))
        {
            $query = $request->get('title');
            $data = DB::table('article')
                ->join('users', 'users.id', '=', 'article.author')

                ->select('article.id as id', 'users.name as author', 'article.title as title', 'article.post as post',
                    'article.created_at as created_at', 'article.updated_at as updated_at')
                ->where('title', 'LIKE', "%{$query}%")
                ->paginate();
            if(count($data) >= 1)
            {
                return PostResource::collection($data);
            }
            else
            {
                return $this->check_post_exist('3');
            }
        }
    }
}
