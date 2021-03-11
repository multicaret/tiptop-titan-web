<?php

namespace App\Http\Controllers;


use App\Mail\Contact;
use App\Models\Post;
use App\Models\Preference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    public function show(Post $page)
    {
        return view('frontend.pages.show', compact('page'));
    }

    public function about()
    {
        $page = Post::find(Post::ABOUT_PAGE_ID);

        return view('frontend.pages.about', compact('page'));
    }

    public function contact()
    {
        $page = Post::find(Post::CONTACT_PAGE_ID);

        return view('frontend.pages.contact', compact('page'));
    }

    public function faq()
    {
        $page = Post::find(Post::FAQ_PAGE_ID);

        return view('frontend.pages.faq', compact('page'));
    }

    public function termsAndConditions()
    {
        $page = Post::find(Post::TERMS_PAGE_ID);

        return view('frontend.pages.show', compact('page'));
    }

    public function privacyPolicy()
    {
        $page = Post::find(Post::PRIVACY_PAGE_ID);

        return view('frontend.pages.show', compact('page'));
    }

    public function contactForm(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => '',
            'subject' => '',
            'message' => 'required',
        ];
        $validator = validator()->make($request->all(), $rules);
        if ($validator->fails()) {
            return '<span class="text-danger">'.$validator->getMessageBag()->first().'</span>';
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
        ];

        Mail::to(Preference::retrieveValue('contact_email'))->send(new Contact($data));

        return '<span class="text-success">'.__('Successfully done!').'</span>';
    }
}
