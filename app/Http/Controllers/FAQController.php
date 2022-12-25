<?php

namespace App\Http\Controllers;

use App\Http\Resources\FaqResource;
use App\Models\FAQ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Error;

class FAQController extends Controller
{

    public function index()
    {
      try {
        $faqs = FAQ::all();
        return response()->json(['status_code' => 200, 'faqs' => FaqResource::collection($faqs)])->setStatusCode(200);
      }
      catch(Error $error) {
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'FAQController, Trying to get all faqs'])->setStatusCode(500);  
      }
    }
    public function getFAQ($id)
    {
      try {
        $faq = new FaqResource(FAQ::find($id));
        return response()->json(['status_code' => 200, 'faq' => $faq])->setStatusCode(200);
      }
      catch(Error $error) {
        return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'FAQController, Trying to get an faq for update'])->setStatusCode(500);  
      
      }
    }
    public function store(Request $request)
    { 
      try {
        $validator = Validator::make($request->all(), [
          'question' => 'required|string',
          'answer' => 'required|string'
        ], [
          'question.required' => ['ar' => 'يرجى تحديد السؤال', 'en' => 'Please specify question'],
          'answer.required' => ['ar' => 'يرجى تحديد الجواب', 'en' => 'Please specify answer']
        ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
        $faq = DB::transaction(function() use($request) {
          $faq = new FAQ();
          $faq->question = $request->question;
          $faq->answer = $request->answer;
          $faq->save();
          return $faq;
        });
        DB::commit();
        return response()->json(['status_code' => 201, 'faq' => $faq])->setStatusCode(201);
    
        
      }
        catch(Error $error) {
          DB::rollBack();
          return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'FAQController, Trying to create an faq'])->setStatusCode(500);  
        
        } 
      }
        public function update(Request $request)
        {
          try {
            $validator = Validator::make($request->all(), [
              'question' => 'required|string',
              'answer' => 'required|string'
            ], [
              'question.required' => ['ar' => 'يرجى تحديد السؤال', 'en' => 'Please specify question'],
              'answer.required' => ['ar' => 'يرجى تحديد الجواب', 'en' => 'Please specify answer']
            ]);
            if($validator->fails()) 
              return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
            $faq = DB::transaction(function() use($request) {
              $faq = FAQ::find($request->id);
              $faq->question = $request->question;
              $faq->answer = $request->answer;
              $faq->save();
              return $faq;
            });
            DB::commit();
            return response()->json(['status_code' => 200, 'faq' => $faq])->setStatusCode(200);
        
            } 
            catch(Error $error) {
              DB::rollBack();
              return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'FAQController, Trying to update an faq'])->setStatusCode(500);  
            
            } 
          }
            

            public function delete($id)
            {
              try {
              FAQ::destroy($id);
              $faqs = FaqResource::collection(FAQ::all());
              return response()->json(['status_code' => 200, 'message' => 'FAQ Deleted', 'faqs' => $faqs])->setStatusCode(200);    
            } 
            catch(Error $error) {
              DB::rollBack();
              return response()->json(['status_code' => 500, 'error' => $error->getMessage(), 'location' => 'FAQController, Trying to delete an faq'])->setStatusCode(500);  
            
            } 
          }
}
