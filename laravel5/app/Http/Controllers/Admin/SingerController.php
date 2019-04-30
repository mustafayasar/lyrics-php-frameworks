<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SingerStore;
use App\Singer;
use App\Song;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Rafwell\Simplegrid\Grid;

class SingerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $grid   = new Grid(Singer::query(), 'Singer');

        $grid->fields([
            'id'        => 'ID',
            'name'      => 'Name',
            'slug'      => 'Slug',
            'hit'       => 'Hit',
            'status'    => 'Status'
        ])->processLine(function($row){
            $row['status'] = Singer::$statuses[$row['status']];

            return $row;
        })->defaultOrder(['hit', 'DESC'])
            ->allowExport(false)
            ->action('Edit', 'singer/{id}/edit')
            ->action('Delete', 'singer/{id}', [
                'confirm'   => 'Do you with so continue?',
                'method'    => 'DELETE',
            ]);

        return view('admin.singer.index', ['grid' => $grid]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.singer.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\SingerStore $request
     * @return \Illuminate\Http\Response
     */
    public function store(SingerStore $request)
    {
        $validated_data = $request->validated();

        Singer::create($validated_data);

        return redirect(route('singer.index'))->with('success', 'Singer is successfully saved');
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
        $singer = Singer::findOrFail($id);

        return view('admin.singer.edit', [
            'singer'    => $singer,
            'statuses'  => Singer::$statuses
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\SingerStore  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SingerStore $request, $id)
    {
        $validated_data = $request->validated();

        Singer::findOrFail($id)->update($validated_data);

        return redirect(route('singer.edit', $id))->with('success', 'Singer is successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Singer::destroy($id)) {
            Song::where(['singer_id' => $id])->delete();

            return redirect(route('singer.index'))->with('success', 'Singer is successfully deleted');
        } else {
            return redirect(route('singer.index'))->with('error', "Singer couldn't be deleted");
        }
    }
}
