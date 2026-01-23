<?php

namespace App\Features\Nhgrc;

use Closure;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\RequestOptions;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Nhgrc
{
    protected $env = 'prod';

    protected $config = [];

    public function __construct()
    {
        $this->env = config('nhgrc.env');
        $this->config = config('nhgrc.' . $this->env);
    }


    /**
     * 获取指定分类的子分类列表 
     *
     * @param array $params
     * @return void
     */
    public function getClassifications($params = [])
    {
        $endpoint = 'agricultural/external/api/getClassifications';

        $client = $this->getClient();

        $result = $client->request($endpoint, [
            'form_params' => $params
        ]);

        return $result;
    }


    /**
     * 获取指定分类的详情
     *
     * @param array $params
     * @return void
     */
    public function getClassificationDetail($params = [])
    {
        $endpoint = 'agricultural/external/api/getClassificationDetail';

        $client = $this->getClient();

        $result = $client->request($endpoint, [
            'form_params' => $params
        ]);

        return $result;
    }



    /**
     * 获取所有分类，平铺的
     *
     * @param array $params
     * @return void
     */
    public function getClassificationTree()
    {
        $endpoint = 'agricultural/external/api/getClassificationTree';

        $client = $this->getClient();

        $result = $client->request($endpoint);

        return $result;
    }


    /**
     * 上传图片
     */
    public function uploadImage(string | Media $media)
    {
        $endpoint = 'agricultural/external/api/uploadImage';

        $client = $this->getClient();

        $content = $this->getImageResource($media);

        $result = $client->request($endpoint, [
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => $content,
                ]
            ],
        ]);

        return $result;
    }



    /**
     * 提交种质数据（单条）
     *
     * @param array $params
     * @return void
     */
    public function submitGermplasm($appraise)
    {
        $params = $this->getAppraiseData($appraise);

        $endpoint = 'agricultural/external/api/submitGermplasm';
        $client = $this->getClient();
        $result = $client->request($endpoint, [
            'form_params' => $params
        ]);

        // @sn todo 这里需要保存 待审核id

        return $result;
    }


    /**
     * 提交种质数据（批量）
     *
     * @param array $params
     * @return void
     */
    public function batchSubmitGermplasm($appraises)
    {
        $items = [];
        foreach ($appraises as $appraise) {
            $items[] = $this->getAppraiseData($appraise);
        }

        $endpoint = 'agricultural/external/api/batchSubmitGermplasm';
        $client = $this->getClient();
        $result = $client->request($endpoint, [
            'form_params' => [
                'items' => $items
            ]
        ]);

        return $result;
    }


    /**
     * 查询提交状态
     *
     * @return void
     */
    public function checkStatus($appraiseId)
    {
        $externalRef = 'resourcedb-' . $appraiseId;

        $endpoint = 'agricultural/external/api/checkStatus';

        $client = $this->getClient();
        $result = $client->request($endpoint, [
            'form_params' => [
                'externalRef' => $externalRef
            ]
        ]);

        return $result;
    }


    /**
     * 获取栏目列表
     *
     * @param array $params
     * @return void
     */
    public function getWebcolumns($params)
    {
        $endpoint = 'agricultural/external/api/getWebcolumns';

        $client = $this->getClient();

        $result = $client->request($endpoint, [
            'form_params' => $params
        ]);

        return $result;
    }


    // 提交文章，明天写


    /**
     * 组装 要提交的数据
     *
     * @param Model $appraise
     * @return void
     */
    protected function getAppraiseData($appraise)
    {
        if ($appraise->getFirstMedia('cover')) {
            try {
                $uploadResult = $this->uploadImage($appraise->getFirstMedia('cover'));
                $imageUrl = $uploadResult['imgurl'] ?? null;
            } catch (\Exception $e) {
                Log::error('上传种质封面失败: appraise-' . $appraise->id . ': ' . $e->getMessage());
            }
        }

        $params = [
            'zzname' => $appraise->name,            // 种质名称
            'zzwwname' => $appraise->en_name,       // 种质外文名
            'xname' => $appraise->species_name,     // 学名
            'kname' => $appraise->subject_name,     // 科名
            'sname' => $appraise->genus_name,       // 属名
            // 'zname' => $appraise->aaaa,          // 没有种名
            'tycode' => $appraise->resource_no,     // 全国统一编号
            'bcdwcode' => $appraise->saveCompany ? "{$appraise->saveCompany->name} (编号：{$appraise->saveCompany->code})" : '',          // 
            'oldarea' => $appraise->country_name . ' ' . ($appraise->country_code == 'CN' ? ($appraise->province_name . ' ' . $appraise->city_name . ' ') : '') . $appraise->address,
            'zztype' => $appraise->germplasm_type,  // 种质类型
            // 'classid' => $appraise->aaaa,        // 类别 id
            // 'memo' => $appraise->aaaa,           // 没有备注
            // 'paramsdata' => $appraise->aaaa,     // 扩展参数（JSON格式）
            'imgurl' => $imageUrl ?? null,          // 种质封面图
            // 'resourceCategory' => $appraise->aaaa,  // 资源类别 
            'mainUse' => $appraise->germplasm_use,      // 主要用途
            // 'germplasmCharacteristics' => $appraise->aaaa,  // 种质特征
            'lxr' => $appraise->saveCompany?->contact,
            'lxdh' => $appraise->saveCompany?->contact_phone,
            'externalRef' => 'resourcedb-' . $appraise->id,
            // 'customParamsJson' => $appraise->aaaa,
        ];

        return $params;
    }


    /**
     * 获取请求客户端
     *
     * @return Client
     */
    protected function getClient()
    {
        return new Client([
            'app_key' => $this->config['app_key'],
            'app_secret' => $this->config['app_secret'],
        ], $this->env);
    }


    /**
     * 获取图片资源
     */
    protected function getImageResource(string | Media $media)
    {
        try {
            if ($media instanceof Media) {
                if (in_array($media->disk, ['public', 'local'])) {
                    // 本地图片
                    return Psr7\Utils::tryFopen($media->getPath(), 'r');
                } else {
                    // 远程图片
                    $client = new GuzzleClient();
                    $response = $client->get($media->getFullUrl(), ['stream' => true]);
                    return $response->getBody()->detach();
                }
            } else {
                $file_url = $media;
                // 本地图片
                return Psr7\Utils::tryFopen($file_url, 'r');
            }
        } catch (\Exception $e) {
            throw new \Exception("获取图片资源失败: " . $e->getMessage());
        }
    }
}
