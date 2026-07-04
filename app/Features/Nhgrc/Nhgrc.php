<?php

namespace App\Features\Nhgrc;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Nhgrc
{
    protected $env = 'prod';

    protected $config = [];

    public function __construct()
    {
        $this->env = config('nhgrc.env');
        $this->config = config('nhgrc.'.$this->env);
    }

    /**
     * 获取指定分类的子分类列表
     *
     * @param  array  $params
     * @return void
     */
    public function getClassifications($params = [])
    {
        // "id": "1",
        // "name": "蔬菜",
        // "parentId": "0",
        // "parentPath": "0",
        // "depth": 1,
        // "hasCustomFields": false

        $endpoint = 'agricultural/external/api/getClassifications';

        $client = $this->getClient();

        $result = $client->request($endpoint, [
            'form_params' => $params,
        ]);

        return $result;
    }

    /**
     * 获取指定分类的详情
     *
     * @param  array  $params
     * @return void
     */
    public function getClassificationDetail($params = [])
    {
        $endpoint = 'agricultural/external/api/getClassificationDetail';

        $client = $this->getClient();

        $result = $client->request($endpoint, [
            'form_params' => $params,
        ]);

        return $result;
    }

    /**
     * 获取所有分类，平铺的
     *
     * @param  array  $params
     * @return void
     */
    public function getClassificationTree($params = [])
    {
        // "id": "1357549223804960",
        // "pId": "1",
        // "title": "甜瓜",
        // "paramsjson": "[{\"key\":\"a1\",\"val\":\"全国统一编号\"},...]"

        $key = 'getClassificationTree-'.md5(json_encode($params));
        $result = through_cache($key, function () {
            $endpoint = 'agricultural/external/api/getClassificationTree';

            $client = $this->getClient();

            $result = $client->request($endpoint);

            return $result;
        }, ttl: 100);

        return $result;
    }

    /**
     * 上传图片
     */
    public function uploadImage(string|Media $media)
    {
        $endpoint = 'agricultural/external/api/uploadImage';

        $client = $this->getClient();

        $content = $this->getImageResource($media);

        $result = $client->request($endpoint, [
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => $content,
                ],
            ],
        ]);

        return $result;
    }

    /**
     * 提交种质数据（单条）
     *
     * @param  array  $params
     * @return void
     */
    public function submitGermplasm($appraise)
    {
        $params = $this->getAppraiseData($appraise);

        Log::error('提交种质信息: '.json_encode($params));

        $endpoint = 'agricultural/external/api/submitGermplasm';
        $client = $this->getClient();
        $result = $client->request($endpoint, [
            'form_params' => $params,
        ]);

        // 保存 待审核id、externalRef
        $appraise->nhgrc_pending_id = $result['pendingId'] ?? null;
        $appraise->nhgrc_external_ref = $params['externalRef'] ?? null;
        $appraise->save();

        Log::error('提交种质信息结果: '.json_encode($result));

        return $result;
    }

    /**
     * 提交种质数据（批量）
     *
     * @param  array  $params
     * @return void
     */
    public function batchSubmitGermplasm($appraises)
    {
        $items = [];
        foreach ($appraises as $appraise) {
            $currentParams = $this->getAppraiseData($appraise);
            $items[] = $currentParams;
            $appraise->nhgrc_external_ref = $currentParams['externalRef'] ?? null;
        }

        $endpoint = 'agricultural/external/api/batchSubmitGermplasm';
        $client = $this->getClient();
        $result = $client->request($endpoint, [
            'form_params' => [
                'items' => json_encode($items),
            ],
        ]);

        $pendingIds = $result['pendingIds'] ?? [];
        foreach ($appraises as $appraise) {
            // @sn todo 这里没办法匹配对应的 pending_id
            $appraise->nhgrc_pending_id = $pendingIds[$appraise->id] ?? null;
            $appraise->save();
        }

        return $result;
    }

    /**
     * 组装 要提交的数据
     *
     * @param  Model  $appraise
     * @return void
     */
    protected function getAppraiseData($appraise)
    {
        if ($appraise->getFirstMedia('cover')) {
            try {
                $uploadResult = $this->uploadImage($appraise->getFirstMedia('cover'));
                $imageUrl = $uploadResult['imgurl'] ?? null;
            } catch (\Exception $e) {
                Log::error('上传种质封面失败: appraise-'.$appraise->id.': '.$e->getMessage());
            }
        }

        $paramsData = $this->getParamsData($appraise);

        $params = [
            'zzname' => $appraise->name,            // 种质名称
            'zzwwname' => $appraise->en_name,       // 种质外文名
            'xname' => $appraise->species_name,     // 学名
            'kname' => $appraise->subject_name,     // 科名
            'sname' => $appraise->genus_name,       // 属名
            // 'zname' => $appraise->aaaa,          // 没有种名
            'tycode' => $appraise->resource_no,     // 全国统一编号
            'bcdwcode' => $appraise->saveCompany ? "{$appraise->saveCompany->name} (编号：{$appraise->saveCompany->code})" : '',          //
            'oldarea' => $appraise->country_name.' '.($appraise->country_code == 'CN' ? ($appraise->province_name.' '.$appraise->city_name.' ') : '').$appraise->address,
            'zztype' => $appraise->germplasm_type,  // 种质类型
            'classid' => $this->getClassid($appraise),        // 类别 id
            // 'memo' => $appraise->aaaa,           // 没有备注
            'paramsdata' => $paramsData['paramsData'],     // 扩展参数（JSON格式）
            'imgurl' => $imageUrl ?? null,          // 种质封面图
            // 'resourceCategory' => $appraise->aaaa,  // 资源类别
            'mainUse' => $appraise->germplasm_use,      // 主要用途
            // 'germplasmCharacteristics' => $appraise->aaaa,  // 种质特征
            'lxr' => $appraise->saveCompany?->contact,
            'lxdh' => $appraise->saveCompany?->contact_phone,
            'externalRef' => 'resourcedb-germplasm-'.$appraise->id,
            'customParamsJson' => $paramsData['customParamsJson'],
        ];

        return $params;
    }

    /**
     * 获取自定义数据
     */
    protected function getParamsData($appraise)
    {
        $groupFields = $appraise->options['fields'];

        $customParamsJson = [];
        $paramsData = [];
        foreach ($groupFields as $groupField) {
            $fields = $groupField['fields'];

            foreach ($fields as $field) {
                $data = $field['data'] ?? [];
                $dataKey = $data['name'] ?? null;
                if ($data && filled($data['name'])) {
                    $customParamsJson[] = [
                        'key' => $dataKey,
                        'val' => $data['value'] ?? null,
                    ];

                    $paramsData[$dataKey] = $data['value'] ?? null;
                }
            }
        }

        return compact('customParamsJson', 'paramsData');
    }

    /**
     * 根据分类信息，匹配 种质分类
     */
    protected function getClassid($appraise)
    {
        // $classificationResult = $this->getClassificationDetail([
        //     'classId' => "1357549223804960"
        // ]);
        // $classificationTree = $classificationResult['data'] ?? [];
        // dd($classificationTree);

        $classificationResult = $this->getClassificationTree();
        $classificationTree = $classificationResult['data'] ?? [];

        $classificationTree = collect($classificationTree);

        $category = $appraise->category;        // 种质分类是单选

        $matchClassification = $classificationTree->firstWhere(function ($item) use ($category) {
            return $category->name == $item['title'];
        });
        $classid = $matchClassification['id'] ?? null;

        return $classid;
    }

    /**
     * 查询提交状态
     *
     * @return void
     */
    public function checkStatus($appraiseId)
    {
        $externalRef = 'resourcedb-germplasm-'.$appraiseId;

        $endpoint = 'agricultural/external/api/checkStatus';

        $client = $this->getClient();
        $result = $client->request($endpoint, [
            'form_params' => [
                'externalRef' => $externalRef,
            ],
        ]);

        return $result;
    }

    /**
     * 获取栏目列表
     *
     * @param  array  $params
     * @return void
     */
    public function getWebcolumns($params)
    {
        $key = 'getWebcolumns-'.md5(json_encode($params));
        $result = through_cache($key, function () use ($params) {
            $endpoint = 'agricultural/external/api/getWebcolumns';

            $client = $this->getClient();

            $result = $client->request($endpoint, [
                'form_params' => $params,
            ]);

            return $result;
        }, ttl: 100);

        return $result;
    }

    /**
     * 提交文章信息 (单条)
     *
     * @param  array  $params
     * @return void
     */
    public function submitArticle($article)
    {
        $params = $this->getArticleData($article);

        Log::error('提交文章信息: '.json_encode($params));

        $endpoint = 'agricultural/external/api/submitArticle';
        $client = $this->getClient();
        $result = $client->request($endpoint, [
            'form_params' => $params,
        ]);

        // 保存 待审核id、externalRef
        $article->nhgrc_pending_id = $result['pendingId'] ?? null;
        $article->nhgrc_external_ref = $params['externalRef'] ?? null;
        $article->save();

        Log::error('提交文章信息结果: '.json_encode($result));

        return $result;
    }

    /**
     * 提交文章信息 (批量)
     *
     * @param  array  $params
     * @return void
     */
    public function batchSubmitArticle($articles)
    {
        $items = [];
        foreach ($articles as $article) {
            $currentParams = $this->getArticleData($article);
            $items[] = $currentParams;
            $article->nhgrc_external_ref = $currentParams['externalRef'] ?? null;
        }

        $endpoint = 'agricultural/external/api/batchSubmitArticle';
        $client = $this->getClient();
        $result = $client->request($endpoint, [
            'form_params' => [
                'items' => json_encode($items),
            ],
        ]);

        $pendingIds = $result['pendingIds'] ?? [];

        foreach ($articles as $article) {
            // @sn todo 这里没办法匹配对应的 pending_id
            $article->nhgrc_pending_id = $pendingIds[$article->id] ?? null;
            $article->save();
        }

        return $result;
    }

    /**
     * 查询提交状态
     *
     * @return void
     */
    public function checkArticleStatus($articleId)
    {
        $externalRef = 'resourcedb-article-'.$articleId;

        $endpoint = 'agricultural/external/api/checkArticleStatus';

        $client = $this->getClient();
        $result = $client->request($endpoint, [
            'form_params' => [
                'externalRef' => $externalRef,
            ],
        ]);

        return $result;
    }

    /**
     * 组装 要提交的数据
     *
     * @param  Model  $article
     * @return void
     */
    protected function getArticleData($article)
    {
        if ($article->getFirstMedia('post_image')) {
            try {
                $uploadResult = $this->uploadImage($article->getFirstMedia('post_image'));
                $imageUrl = $uploadResult['imgurl'] ?? null;
            } catch (\Exception $e) {
                Log::error('上传文章图片失败: article-'.$article->id.': '.$e->getMessage());
            }
        }

        $params = [
            'title' => $article->title,            // 文章标题
            'content' => $article->content?->content,       // 文章内容（支持HTML格式）
            'briefintroduction' => $article->description,     // 文章简介
            'webcolumnid' => $this->getWebcolumnId($article),     // 栏目ID
            'photo' => $imageUrl ?? null,          // 封面图（先调用uploadImage获取）
            'newstype' => 0,     // 类型：0-普通新闻, 1-轮播图, 2-缩略图
            'startdate' => isset($article->published_at) ? $article->published_at?->format('Y-m-d') : $article->created_at?->format('Y-m-d'),     // 发布日期（格式：YYYY-MM-DD）
            'externalRef' => 'resourcedb-article-'.$article->id,     // 外部系统引用ID
        ];

        return $params;
    }

    /**
     * 根据分类信息，匹配 栏目 id
     */
    protected function getWebcolumnId($article)
    {
        $webcolumnResult = $this->getWebcolumns([]);
        $webcolumns = $webcolumnResult['data'] ?? [];

        $webcolumns = collect($webcolumns);

        $categories = $article->categories;

        $matchWebcolumn = $webcolumns->firstWhere(function ($item) use ($categories) {
            return $categories->where('name', $item['name'])->first();
        });
        $webcolumnId = $matchWebcolumn['id'] ?? null;

        return $webcolumnId;
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
    protected function getImageResource(string|Media $media)
    {
        try {
            if ($media instanceof Media) {
                if (in_array($media->disk, ['public', 'local'])) {
                    // 本地图片
                    return Psr7\Utils::tryFopen($media->getPath(), 'r');
                } else {
                    // 远程图片
                    $client = new GuzzleClient;
                    $response = $client->get($media->getFullUrl(), ['stream' => true]);

                    return $response->getBody()->detach();
                }
            } else {
                $file_url = $media;

                // 本地图片
                return Psr7\Utils::tryFopen($file_url, 'r');
            }
        } catch (\Exception $e) {
            throw new \Exception('获取图片资源失败: '.$e->getMessage());
        }
    }
}
