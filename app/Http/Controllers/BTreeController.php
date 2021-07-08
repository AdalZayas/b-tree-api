<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;



class BTreeController extends Controller
{
  // $arr = array(8,3,1,6,4,7,10,14,13);

  public function index()
  {
    $helloWorld = 'hello world';
    return($helloWorld);
  }

  public function heigth(Request $request)
  {
 

    return response()->json([
      'data' => [
        'heigth' => $tree->traverse('inorder')
      ]
    ]);
  }
}
