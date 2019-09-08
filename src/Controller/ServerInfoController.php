<?php
/**
 * Created by PhpStorm.
 * User: pusty
 * Date: 10.04.2018
 * Time: 23:09
 */

namespace App\Controller;

use Exception;
use SimpleXMLElement;
use xPaw\SourceQuery\Exception\SourceQueryException;
use xPaw\SourceQuery\SourceQuery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Entity\Servers;
use ts3admin;

class ServerInfoController extends Controller
{

    /**
     * @Route("/v1/server/add", name="add_server")
     * @Method({"POST"})
     */
    public function addServer(Request $request)
    {
        $server = new Servers();
        $server->setServername('GOPROPA.pl');
        $server->setMaxPlayers(0);
        $server->setCurrentPlayers(0);
        $server->setCurrentMap('');
        $server->setStatus(0);
        $server->setHost($request->get('host'));
        $server->setPort($request->get('port'));
        if ($request->get('token') == md5(date('Y-m-d'))) {
            try {
                $em = $this->getDoctrine();
                $entity = $em->getManager();
                $entity->persist($server);
                $entity->flush();
                $tab['error'] = 'SUCCESS';
            } catch (Exception $e) {
                $tab['error'] = 'ERROR';
            }
        } else $tab['error'] = 'WRONG_TOKEN';
        $response = new JsonResponse($tab, Response::HTTP_OK);
        return $response;

    }

    /**
     * @Route("/v1/server/delete", name="delete_server")
     * @Method({"POST"})
     */
    public function deleteServer(Request $request)
    {
        $em = $this->getDoctrine();
        $server = $em->getRepository(Servers::class)->findOneBy(["host" => $request->get('host'), "port" => $request->get('port')]);
        if ($request->get('token') == md5(date('Y-m-d'))) {
            try {
                if ($server != null) {
                    $entity = $em->getManager();
                    $entity->remove($server);
                    $entity->flush();
                    $tab['error'] = 'SUCCESS';
                } else $tab['error'] = 'NO_SERVER_FOUND';

            } catch (Exception $e) {
                var_dump($e);
            }
        } else $tab['error'] = 'WRONG_TOKEN';
        $response = new JsonResponse($tab, Response::HTTP_OK);
        return $response;

    }

    /**
     * @Route("/v1/servers/update", name="update_servers")
     * @Method({"GET", "POST"})
     */
    public function updateinfo(Request $request)
    {
        $em = $this->getDoctrine();
        $servers = $em->getRepository(Servers::class)->findAll();
        if (is_array($servers)) {
            foreach ($servers as $server) {
                try {
                    $tab['error'] = 'SUCCESS';
                    if ($server->getType() == 'csgo') {
                        $query = new SourceQuery();
                        if ($server->getPort() == NULL) $port = 27015; else $port = $server->getPort();
                        $query->connect($server->getHost(), $port, 1, SourceQuery::SOURCE);
                        $info = $query->getInfo();
                        if ($info) {
                            $procent = round($info['Players']/$info['MaxPlayers']*100);
                            $server->setType($info['ModDir']);
                            $server->setServername($info['HostName']);
                            $server->setMaxPlayers($info['MaxPlayers']);
                            $server->setCurrentPlayers($info['Players']);
                            $server->setCurrentMap($info['Map']);
                            $server->setPercent($procent);
                            $server->setStatus(1);
                            if($procent <= 19){
                                $server->setPercentColor('#3498DB');
                            } else if($procent <=39 && $procent > 19){
                                $server->setPercentColor('#2ECC71');
                            } else if($procent <=59 && $procent > 39){
                                $server->setPercentColor('#428BCA');
                            } else if($procent <=79 && $procent > 59){
                                $server->setPercentColor('#F1C40F');
                            } else if($procent <=89 && $procent > 79){
                                $server->setPercentColor('#F17218');
                            } else if($procent > 89) {
                                $server->setPercentColor('#E74C3C');
                            }


                        } else {
                            $server->setStatus(0);
                            $server->setCurrentMap('n/a');
                            $server->setCurrentPlayers(0);
                            $server->setMaxPlayers(0);
                            $server->setPercent(0);
                            $server->setPercentColor('#3498DB');
                        }
                        $query->Disconnect();
                    } else if ($server->getType() == 'ts3') {

                        $query = new ts3admin($server->getHost(), $server->getQueryport(), 1);
                        if ($query->getElement('success', $query->connect())) {
                            $query->login($server->getQueryLogin(), $server->getQueryPwd());
                            $query->selectServer(9987);
                            $info = $query->serverInfo();
                            $server->setStatus(1);
                            $server->setServername($info['data']['virtualserver_name']);
                            $server->setMaxPlayers($info['data']['virtualserver_maxclients']);
                            $server->setCurrentPlayers($info['data']['virtualserver_clientsonline'] - $info['data']['virtualserver_queryclientsonline']);

                            $procent = round(($info['data']['virtualserver_clientsonline'] - $info['data']['virtualserver_queryclientsonline'])/$info['data']['virtualserver_maxclients']*100);
                            $server->setPercent($procent);
                            if($procent <= 19){
                                $server->setPercentColor('#3498DB');
                            } else if($procent <=39 && $procent > 19){
                                $server->setPercentColor('#2ECC71');
                            } else if($procent <=59 && $procent > 39){
                                $server->setPercentColor('#428BCA');
                            } else if($procent <=79 && $procent > 59){
                                $server->setPercentColor('#F1C40F');
                            } else if($procent <=89 && $procent > 79){
                                $server->setPercentColor('#F17218');
                            } else if($procent > 89) {
                                $server->setPercentColor('#E74C3C');
                            }

                        } else {
                            $server->setStatus(0);
                            $server->setCurrentPlayers(0);
                            $server->setMaxPlayers(0);
                            $server->setPercent(0);
                            $server->setPercentColor('#3498DB');
                        }
                        $query->logout();
                    }


                } catch (SourceQueryException $e) {
                    $server->setStatus(0);
                    echo $e;
                    $tab['error'] = 'ERROR';
                    $tab['exception'] = $e->getMessage();
                } catch (Exception $e) {
                    echo $e->getMessage();
                    echo $e->getLine();
                    echo $e->getFile();
                    $tab['error'] = 'ERROR';
                    $tab['exception'] = $e->getMessage();
                }
                $entity = $em->getManager();
                $entity->flush();
            }
        } else $tab['error'] = 'ERROR';
        $response = new JsonResponse($tab, Response::HTTP_OK);
        return $response;

    }

    /**
     * @Route("/v1/server/info", name="get_server_info")
     * @Method({"POST"})
     */
    public function getServerInfo(Request $request)
    {
        $em = $this->getDoctrine();
        $server = $em->getRepository(Servers::class)->findOneBy(["host" => $request->get('host'), "port" => $request->get('port')]);
        if ($server) {
            $tab['error'] = 'SUCCESS';
            $tab['server']['serverId'] = $server->getServerId();
            $tab['server']['host'] = $server->getHost();
            $tab['server']['port'] = $server->getPort();
            $tab['server']['status'] = $server->getStatus();
            $tab['server']['maxPlayers'] = $server->getMaxPlayers();
            $tab['server']['currentPlayers'] = $server->getCurrentPlayers();
            $tab['server']['map'] = $server->getCurrentMap();
        } else $tab['error'] = 'ERROR';
        $response = new JsonResponse($tab, Response::HTTP_OK);
        return $response;

    }

    /**
     * @Route("/v1/servers/info", name="get_server_info")
     * @Method({"GET"})
     */
    public function getServersInfo(Request $request)
    {
        $em = $this->getDoctrine();
        $serversy = $em->getRepository(Servers::class)->findAll();
        if ($serversy != NULL) {
            $servers = array();
            $tab['error'] = 'SUCCESS';
            if (is_array($serversy)) {
                $i = 0;

                foreach ($serversy as $server) {
                    $i++;
                    $serverek = array(
                        'serverId' => $server->getServerId(),
                        'type' => $server->getType(),
                        'host' => $server->getHost(),
                        'port' => $server->getPort(),
                        'hostname' => $server->GetServername(),
                        'status' => $server->getStatus(),
                        'maxPlayers' => $server->getMaxPlayers(),
                        'currentPlayers' => $server->getCurrentPlayers(),
                        'map' => $server->getCurrentMap()

                    );
                    array_push($servers, $serverek);
                }
            } else {
                $serverek = array(
                    'serverId' => $serversy->getServerId(),
                    'type' => $serversy->getType(),
                    'host' => $serversy->getHost(),
                    'port' => $serversy->getPort(),
                    'hostname' => $serversy->GetServername(),
                    'status' => $serversy->getStatus(),
                    'maxPlayers' => $serversy->getMaxPlayers(),
                    'currentPlayers' => $serversy->getCurrentPlayers(),
                    'map' => $serversy->getCurrentMap()

                );
                array_push($servers, $serverek);
            }
            $tab['servers'] = $servers;
        } else $tab['error'] = 'ERROR';

        $response = new JsonResponse($tab, Response::HTTP_OK);
        $response->setEncodingOptions($response->getEncodingOptions() | JSON_PRETTY_PRINT);
        return $response;

    }

    /**
     * @Route("/", name="home")
     * @Method({"GET", "POST"})
     */
    public function home()
    {
        $tab['error'] = 'WRONG_REQUEST';
        $response = new JsonResponse($tab, Response::HTTP_OK);
        $response->setEncodingOptions($response->getEncodingOptions() | JSON_PRETTY_PRINT);
        return $response;
    }

    /**
     * @Route("/v1/steam/group", name="get_steamgroup_info")
     * @Method({"GET"})
     */
    public function steamGroup()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://steamcommunity.com/groups/gopropa/memberslistxml/?xml=1');
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $retValue = curl_exec($ch);
        curl_close($ch);
        $xml = new SimpleXMLElement($retValue);
        $tab['address'] = 'https://steamcommunity.com/groups/gopropa/';
        $tab['allMembers'] = (string)$xml->groupDetails->memberCount;
        $tab['inGame'] = (string)$xml->groupDetails->membersInGame;
        $tab['online'] = (string)$xml->groupDetails->membersOnline;
        $tab['error'] = 'SUCCESS';
        $response = new JsonResponse($tab, Response::HTTP_OK);
        $response->setEncodingOptions($response->getEncodingOptions() | JSON_PRETTY_PRINT);
        return $response;
    }
}