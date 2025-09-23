<?php

namespace App\Http\Controllers;

use App\Dtos\TagResourceDto;
use App\Http\Requests\Tags\CreateTag;
use App\Http\Requests\Tags\CreateTagRequest;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Tag::all()->map(fn($tag) => ["id"=> $tag->id, "name" => $tag->name]);
    }

    /**
     *  Create a new tag if it doesn't exists
     */
    public function store(CreateTagRequest $request)
    {
        $safe = $request->validated();
        
        //Verify if the tag exists already if it is return then
        if(Tag::where('name', $safe['name'])->exists()) return Tag::where('name', $safe['name'])->first();
        return Tag::create($safe);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        $data = new TagResourceDto($tag);
        return response()->json($data->toArray());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        //
    }
}
