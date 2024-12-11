<?php

namespace Mydiabeteshome\ICD10;

use Exception;

class ICD10 extends ICD10ServiceProvider
{

    private $icd10Resource = 'https://clinicaltables.nlm.nih.gov/api/icd10cm/v3/search';


    /**
     * Objective of the function is to get ICD10 Codes via the US Government API
     * @param int $search_term search code or title
     * @param array $max_list limit result
     * @param array $search_phase
     */
    public function getIcd10CodeData($search_term, $max_list = 99999999, $search_phase = 'code,name')
    {
        try {
            $cURLConnection = curl_init($this->icd10Resource . '?sf=' . $search_phase . '&terms=' . $search_term . '&maxList=' . $max_list);
            curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
                'Accept: application/json',
                'content-type: application/json'
            ));
            $apiResponse = curl_exec($cURLConnection);
            curl_close($cURLConnection);
            if (!empty($apiResponse)) {
                $apiResponse = json_decode($apiResponse);
                if (!empty($apiResponse)) {
                    $data = [];
                    if (!empty($apiResponse[3])) {
                        $codeList = $apiResponse[3];
                        if (!empty($codeList)) {
                            foreach ($codeList as $code) {
                                if(!empty($code[0]) && !empty($code[1])){
                                    $data[]=['code'=>$code[0],'title'=>$code[1]];
                                }
                            }
                        }
                    }
                    return ['status' => 200, 'data' => $data];
                } else {
                    return ['status' => 404, 'api_response'=>json_encode($apiResponse)];
                }
            } else {
                return ['status' => 404,'api_response'=>json_encode($apiResponse)];
            }
        } catch (Exception $e) {
            
            throw new Exception($e->getMessage(), 500);
        }
    }
}
