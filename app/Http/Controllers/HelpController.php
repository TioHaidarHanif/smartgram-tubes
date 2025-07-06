<?php

namespace App\Http\Controllers;

use App\Models\HelpTopic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class HelpController extends Controller
{
    public function index()
    {
        $helpTopics = HelpTopic::published()
            ->ordered()
            ->get();

        return view('help.index', compact('helpTopics'));
    }

    public function show($slug)
    {
        $helpTopic = HelpTopic::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $relatedTopics = HelpTopic::published()
            ->where('id', '!=', $helpTopic->id)
            ->ordered()
            ->take(5)
            ->get();

        return view('help.show', compact('helpTopic', 'relatedTopics'));
    }

    // Admin methods
    public function admin()
    {
        $helpTopics = HelpTopic::orderBy('order')->get();
        return view('admin.help.index', compact('helpTopics'));
    }

    public function create()
    {
        return view('admin.help.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'order' => 'required|integer',
            'is_published' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $helpTopic = HelpTopic::create([
            'title' => $request->title,
            'content' => $request->content,
            'slug' => Str::slug($request->title),
            'order' => $request->order,
            'is_published' => $request->has('is_published'),
        ]);

        return redirect()->route('admin.help')->with('success', 'Help topic created successfully!');
    }

    public function edit($id)
    {
        $helpTopic = HelpTopic::findOrFail($id);
        return view('admin.help.edit', compact('helpTopic'));
    }

    public function update(Request $request, $id)
    {
        $helpTopic = HelpTopic::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'order' => 'required|integer',
            'is_published' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $helpTopic->update([
            'title' => $request->title,
            'content' => $request->content,
            'slug' => Str::slug($request->title),
            'order' => $request->order,
            'is_published' => $request->has('is_published'),
        ]);

        return redirect()->route('admin.help')->with('success', 'Help topic updated successfully!');
    }

    public function destroy($id)
    {
        $helpTopic = HelpTopic::findOrFail($id);
        $helpTopic->delete();

        return redirect()->route('admin.help')->with('success', 'Help topic deleted successfully!');
    }
}
