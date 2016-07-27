<?php

namespace AIBattle\Helpers;

use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Facades\Datatables;

class DuelsDataTable {
    public static function transform($data, $tournament) {

        $userName = Auth::user()->username;

        return Datatables::of($data)
            ->filter(function($query) use(&$request) {
                // custom filtering for our purposes
                if ($request != null) {
                    if ($request->has('user1')) {
                        $query->where('usr1.username', '=', $request->get('user1'));
                    }

                    if ($request->has('user2')) {
                        $query->where('usr2.username', '=', $request->get('user2'));
                    }
                }
            })
            ->editColumn('status', function($data) use(&$userName, &$tournament) {
                // make sure of status

                $data = (array)$data;

                $statusArray = explode(' ', trim($data['status']));
                $linkClass = "info";

                if (count($statusArray) > 1) {
                    if ($data['user1'] == $userName || $data['user2'] == $userName) {
                        if (str_contains($data['status'], 'WIN')) {
                            if ($data['user' . $statusArray[1]] == $userName) {
                                $linkClass = "success";
                            } else {
                                $linkClass = "danger";
                            }
                        } else {
                            // something wrong, check if it our fail or not
                            if ($data['user' . $statusArray[1]] == $userName) {
                                // something wrong with us
                                $linkClass = "danger";
                            }
                        }
                    }
                } else {
                    if ($statusArray[0] == "TIE") {
                        $linkClass = "primary";
                    }
                }

                $action = '#';
                if ($statusArray[0] != "W") {
                    $action = action('DownloadController@downloadLog', [$tournament->id, $data['id']]);
                }

                return '<a href="' . $action . '" class="btn-xs btn-' . $linkClass . '"><i class="glyphicon glyphicon-download-alt"></i> ' . $data['status'] . '</a>';
            })
            ->editColumn('hasVisualizer', function($data) use(&$tournament) {

                $data = (array)$data;
                $statusArray = explode(' ', trim($data['status']));
                $href = "#";

                if ($statusArray[0] != "W") {
                    $href = url('tournaments/' . $tournament->id . '/training/' . $data['id']);
                }

                return '<a href="' . $href . '"  target="_blank" class="btn-xs btn-warning"><i class="glyphicon glyphicon-play"></i> ' . trans('tournaments/strategies.trainingViewGame') . '</a>';
            })
            ->make(true);
    }
}