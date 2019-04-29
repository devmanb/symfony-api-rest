<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\JsonResponse;
use GuzzleHttp\Client;
/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/localisation", name="localisation", methods={"GET"})
     */
    public function index(Request $request)
    {
        $data = json_decode(
            $request->getContent(),
            true
        );
        if(is_null($data))
        {
            $mylat=$request->get('lat');
            $mylong=$request->get('lon');
        }else
        {
            $mylat=$data['lat'];
            $mylong=$data['lon'];
        }

        $client = new Client();

        $response = $client->request('GET', "http://api.openweathermap.org/data/2.5/weather?"
        ,['query'=>[
            'lat'=>$mylat, 'lon'=>$mylong,'lang'=>'fr','units'=>'metric','appid'=>'1e4bb6b0352cb974596a6d35327b606e'
            ]]);
//        $crawler = $client->submit(");
        return  $this->json($response->getBody());
        //return $this->json(['name' => 'temp','temp'=>'temp']);
    }

    /**
     * @Route("/inscription", name="inscription")
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder)
    {

        $data = json_decode(
            $request->getContent(),
            true
        );
        var_dump($data['_username']);
        $validator = Validation::createValidator();

        $constraint = new Assert\Collection(array(
            // the keys correspond to the keys in the input array
            '_username' => new Assert\Length(array('min' => 1)),
            '_password' => new Assert\Length(array('min' => 1)),
            '_username' => new Assert\Email(),
        ));
        $violations = $validator->validate($data, $constraint);

        if ($violations->count() > 0) {
            return new JsonResponse(["error" => (string)$violations], 500);
        }

        $em = $this->getDoctrine()->getManager();

        $username = $data['_username'];
        $password = $data['_password'];

        $user = new User();
        $user->setRoles(['ROLE_USER']);
       // $user->setUsername($username);
        $user->setEmail($username);
        $user->setPassword($encoder->encodePassword($user, $password));
        $em->persist($user);
        $em->flush();

        return $this->json(['_username' => $username,'created'=>'ok']);
    }

    public function api()
    {
        return new Response(sprintf('Logged in as %s', $this->getUser()->getUsername()));
    }
}
