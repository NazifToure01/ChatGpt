<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Orhanerday\OpenAi\OpenAi;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(Request $request): Response
    {
        $open_ai_key = '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';
        $open_ai = new OpenAi($open_ai_key);


        $quizimage = $request->get('quiz-image');
        if ($quizimage){
            $image = $open_ai->image([
                "prompt" => $quizimage,
                "n" => 1,
                "size" => "256x256",
                "response_format" => "url",
            ]);
            $imageObjet = json_decode($image, true);

          $imageUrl =  $imageObjet['data'][0]['url'];


        } else{
            $imageUrl ='';
        }


        $question = $request->get('quiz');
        if ($question){

            $complete = $open_ai->completion([
                'model' => 'text-davinci-002',
                'prompt' => $question,
                'temperature' => 0.9,
                'max_tokens' => 150,
                'frequency_penalty' => 0,
                'presence_penalty' => 0.6,
            ]);
            $array = json_decode($complete, true);
            $reponse = $array['choices'][0]['text'];
        } else{
            $reponse ='';
        }


        return $this->render('home/index.html.twig', [
            'reponse' => $reponse,
            'imageUrl'=>$imageUrl
        ]);
    }
}
