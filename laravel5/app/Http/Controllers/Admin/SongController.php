<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SongStore;
use App\Singer;
use App\Song;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Rafwell\Simplegrid\Grid;

class SongController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $grid   = new Grid(Song::query()->with('singer'), 'Song');

        $grid->fields([
            'id'        => 'ID',
            'title'     => 'Title',
            'slug'      => 'Slug',
            'singer_id' => 'Singer',
            'hit'       => 'Hit',
            'status'    => 'Status'
        ])->processLine(function($row){
            $row['status'] = Song::$statuses[$row['status']];
            $row['singer_id'] = $row['singer']['name'];

            return $row;
        })->defaultOrder(['id', 'DESC'])
            ->allowExport(false)
            ->action('Edit', 'song/{id}/edit')
            ->action('Delete', 'song/{id}', [
                'confirm'   => 'Do you with so continue?',
                'method'    => 'DELETE',
            ]);

        return view('admin.song.index', ['grid' => $grid]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.song.create', ['singers' => Singer::selectList()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  SongStore  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SongStore $request)
    {
        $validated_data = $request->validated();

        Song::create($validated_data);

        return redirect(route('song.index'))->with('success', 'Song is successfully saved');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->edit($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $song = Song::findOrFail($id);

        return view('admin.song.edit', [
            'song'      => $song,
            'singers'   => Singer::selectList(),
            'statuses'  => Singer::$statuses
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  SongStore $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SongStore $request, $id)
    {
        $validated_data = $request->validated();

        Song::whereId($id)->update($validated_data);

        return redirect(route('song.edit', $id))->with('success', 'Song is successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Song::destroy($id)) {
            return redirect(route('song.index'))->with('success', 'Song is successfully deleted');
        } else {
            return redirect(route('song.index'))->with('error', "Song couldn't be deleted");
        }
    }
}
