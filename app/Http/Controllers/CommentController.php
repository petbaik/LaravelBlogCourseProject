<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
class CommentController extends Controller
{
    public function postComment(Request $request){
    	$data = $request->all();
    	DB::table('comments')->insert(['post_id'=>$data['post_id'],'user_id'=>Auth::user()->id,'comment'=>$data['comment']]);

    	return redirect()->back()->with('msg','Вашият коментар беше добавен успешно');
    }

    public function getComments(){
    	$comments = "";
    	if(Auth::user()->role == "author"){
    		$comments = DB::table('comments')
    		->join('posts','posts.id','comments.post_id')
    		->where('posts.author',Auth::user()->email)
    		->select('posts.*','comments.*','comments.id as comment_id')
            ->orderBy('comments.id','desc')
    		->get();
    	}else if(Auth::user()->role == "admin"){
    		$comments = DB::table('comments')
    		->join('posts','posts.id','comments.post_id')
    		->select('posts.*','comments.*','comments.id as comment_id')
            ->orderBy('comments.id','desc')
    		->get();
    	}
    	
    	return view('admin.comments')->with(['comments'=>$comments]);
    }

    public function deleteComment($id){

    	DB::table('comments')->where('id',$id)->delete();

    	return redirect()->back()->with('msg','Коментарът изтрит успешно');
    }
}
