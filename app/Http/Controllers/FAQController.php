<?php

namespace App\Http\Controllers;

use App\Http\Resources\FaqResource;
use App\Models\FAQ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class FAQController extends Controller
{

    public function index()
    {
        $faqs = FAQ::all();
        return response()->json(['status_code' => 200, 'faqs' => FaqResource::collection($faqs)])->setStatusCode(200);

    }
    public function getFAQ($id)
    {
        $faq = new FaqResource(FAQ::find($id));
        return response()->json(['status_code' => 200, 'faq' => $faq])->setStatusCode(200);

    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
          'ar_question' => 'required|string',
          'ar_answer' => 'required|string',
          'en_question' => 'required|string',
          'en_answer' => 'required|string',
        ]);
        if($validator->fails()) 
          return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
        
        $faq = new FAQ();
        $faq->ar_question = $request->ar_question;
        $faq->ar_answer = $request->ar_answer;
        $faq->en_question = $request->en_question;
        $faq->en_answer = $request->en_answer;
        $faq->save();
        return response()->json(['status_code' => 201, 'faq' => $faq])->setStatusCode(201);
    
        }

        public function update(Request $request)
        {
            $validator = Validator::make($request->all(), [
              'ar_question' => 'required|string',
              'ar_answer' => 'required|string',
              'en_question' => 'required|string',
              'en_answer' => 'required|string',
            ]);
            if($validator->fails()) 
              return response()->json(['status_code' => 422, 'message' => 'Unacceptable Entity', 'errors' => $validator->errors()])->setStatusCode(422);
            
            $faq = FAQ::find($request->id);
            $faq->ar_question = $request->ar_question;
            $faq->ar_answer = $request->ar_answer;
            $faq->en_question = $request->en_question;
            $faq->en_answer = $request->en_answer;
            $faq->save();
            return response()->json(['status_code' => 200, 'faq' => $faq])->setStatusCode(200);
        
            }

            public function delete($id)
            {
              FAQ::destroy($id);
              $faqs = FaqResource::collection(FAQ::all());
              return response()->json(['status_code' => 200, 'message' => 'FAQ Deleted', 'faqs' => $faqs])->setStatusCode(200);    
            }
}
