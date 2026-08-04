<?php

namespace App\Http\Livewire;

use Livewire\Component;

class LikePost extends Component
{   
    public $post;
    public $isLiked;
    public $likes; 

    public function mount($post)
    {
       $this->isLiked = auth()->check() && $post->checkLike(auth()->user());
       $this->likes = $post->likes()->count();
    } 

    public function like() 
    {
        if (auth()->guest()) {
            return redirect()->route('login');
        }

        if( $this->post->checkLike(auth()->user() )) {
            $this->post->likes()
                ->where('user_id', auth()->id())
                ->delete();
            $this->isLiked = false;
            $this->likes = max(0, $this->likes - 1);
        } else {
            $this->post->likes()->firstOrCreate([
                'user_id' => auth()->user()->id
            ]);   
            $this->isLiked = true;
            $this->likes++;
        }
    }
    
    public function render()
    {
        return view('livewire.like-post');
    }
}
