<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

class Node {

  public $node;
  public $left;
  public $right;
  public $level;

  public function __construct($node) {
    $this->node = $node;
    $this->left = NULL;
    $this->right = NULL;
    $this->level = NULL;
  }

  public function __toString() {
      return "$this->node";
  }
}

class SearchBinaryTree {

  public $root;

  public function  __construct() {
    $this->root = NULL;
  }

  public function create($node) {
    if($this->root == NULL) {
      $this->root = new Node($node);
    } else {
      $current = $this->root;
      while(true) {
        if($node < $current->node) {
          if($current->left) {
              $current = $current->left;
          } else {
              $current->left = new Node($node);
              break;
          }
        } else if($node > $current->node){
          if($current->right) {
              $current = $current->right;
          } else {
              $current->right = new Node($node);
              break; 
          }
        } else {
          break;
        }
      }
    }
  }
  

  public function array_search_id($search_value, $array, $id_path) {
    if(is_array($array) && count($array) > 0) {
        foreach($array as $key => $value) {
            $temp_path = $id_path;
            // Adding current key to search path
            array_push($temp_path, $key);
            // Check if this value is an array
            // with atleast one element
            if(is_array($value) && count($value) > 0) {
                $res_path = $this->array_search_id(
                        $search_value, $value, $temp_path);
                if ($res_path != null) {
                    return $res_path;
                }
            }
            else if($value == $search_value) {
                return $temp_path;
            }
        }
    }
    return null;
  }
  public function get_neighbors($node){
    $data = json_decode(json_encode($this->root), true);
    $ids = $this->array_search_id($node, $data, array());

    foreach ($ids as $id ) {
      if ($id != 'node') {
        # code...
        $data = $data[$id];
      }

    }
    // dd($data);
    return($data);
  }

public function BFT() {

  $node = $this->root;
  $node->level = 1;
  $queue = array($node);
  $out = array();
  $level = $node->level;

  while(count($queue) > 0) {

    $current_node = array_shift($queue);

    if($current_node->level > $level) {
        $level++;
        array_push($out);
    }

    array_push($out,$current_node->node);

    if($current_node->left) {
      $current_node->left->level = $level + 1;
      array_push($queue,$current_node->left);
    }

    if($current_node->right) {
      $current_node->right->level = $level + 1;
      array_push($queue,$current_node->right);
    }
  }
    $data = json_decode(json_encode($node), true);
    return ([
      'level' => $level,
      'bfs'=>$out]);
  }
}


class BTreeController extends Controller
{
  public function heigth(Request $request)
  {
    $validator = Validator::make($request->all(), [
        'toTree' => 'required|array',
        'toTree.*' => 'integer',],400);

    if ($validator->fails()) {
      return response()
        ->json(['data'=>$validator->errors()])
        ->withCallback($request->input('callback'));
    }
    
    $arr = $request->input('toTree');
    $tree = new SearchBinaryTree();
    for($i=0,$n=count($arr);$i<$n;$i++) {
      $tree->create($arr[$i]);
    }

    $result = $tree->BFT();

    return response()->json([
      'data' => [
        'heigth' => $result['level'],
        ]
    ]);
  }

  public function neighbors(Request $request){

    $validator = Validator::make($request->all(), [
        'toTree' => 'required|array',
        'toTree.*' => 'integer',
        'node' => 'required|numeric']);

    if ($validator->fails()) {
      return response()->json(['data'=>$validator->errors()],400);
    }

    $arr = $request->input('toTree');
    $tree = new SearchBinaryTree();
    for($i=0,$n=count($arr);$i<$n;$i++) {
      $tree->create($arr[$i]);
    }

    $neighbors = $tree->get_neighbors($request->input('node'));

    return response()->json([
      'data'=> [
        'node' => $neighbors['node'],
        'neighbors' => [
          'left' => !$neighbors['left'] ? null : $neighbors['left']['node'] ,
          'right' => !$neighbors['right'] ? null : $neighbors['right']['node'],
        ]
      ]
    ]);
  }

  public function bfs(Request $request){

    $validator = Validator::make($request->all(), [
        'toTree' => 'required|array',
        'toTree.*' => 'integer',]);

    if ($validator->fails()) {
      return response()->json(['data'=>$validator->errors()],400);
    }

    $arr = $request->input('toTree');

    $tree = new SearchBinaryTree();
    for($i=0,$n=count($arr);$i<$n;$i++) {
      $tree->create($arr[$i]);
    }

    $result = $tree->BFT();

    return response()->json([
      'data' => [
        'bfs' => $result['bfs'],
        ]
    ],200);
  }
}