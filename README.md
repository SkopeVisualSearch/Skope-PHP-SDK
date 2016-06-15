#Skope PHP 软件开发工具包(SDK)

----
##目录
 1. [概要](#1-概要)
 2. [初始运行](#2-初始运行)
 3. [建立图片索引库](#3-建立图片索引库)
	  - 3.1 [上传图片](#31-上传图片)
	  - 3.2 [更新图片](#32-更新图片)
	  - 3.3 [删除图片](#33-删除图片)
 4. [图片搜索](#4-图片搜索)
	  - 4.1 [内部搜索](#41-内部搜索)
	  - 4.2 [颜色搜索](#42-颜色搜索)
	  - 4.3 [上传搜索](#43-上传搜索)
	  - 4.4 [搜索区域](#44-搜索区域)
	  - 4.5 [像素调整](#45-像素调整)
	  - 4.6 [结果显示](#46-结果显示)
	  - 4.7 [自动识别](#47-自动识别)
 5. [高级参数搜索](#5-高级参数搜索)
	  - 5.1 [元数据检索](#51-元数据检索)
	  - 5.2 [结果筛选](#52-结果筛选)
	  

----

##1. 概要
Skope视觉搜索引擎是基于大数据和深度神经网络学习的人工智能图像识别与搜索工具。Skope提供精确，高效，多维的定制化图片管理服务与解决方案，可应用场景有：电商商品同款推荐，街拍快照视觉搜索，用户喜好深度分析，商品库精准管理，手写面单自动分拣，AR/VR的识别应用等等。

Skope API说明文档可见于 [API](http://www.owlapi.com/api.html). 建议先熟读API说明文档。

Skope PHP SDK 是一款基于Skope视觉搜索引擎的开源软件开发工具包。在PHP框架里，此SDK展示搜索与识别的全部应用代码范例，为开发者提供一个轻松快捷的开发环境。代码范例可见于 [Skope PHP SDK](https://github.com/SkopeVisualSearch/Skope-PHP-SDK), 其中的SkopeSearch.php是本说明的主要讨论对象[SkopeSearch.php](https://github.com/SkopeVisualSearch/Skope-PHP-SDK/blob/library/SkopeSearch.php).

 现行版本: 1.0.1
 
 最低要求: php5 and php5-curl


##2. 初始运行

SDK和说明可在[Skope PHP SDK](https://github.com/SkopeVisualSearch/Skope-PHP-SDK)下载。下载后将工具包解压并安放至开发者自己的项目目录里。

初始运行时需要Skope的认证公钥和密钥。钥匙在注册并申请Skope帐户时会由后台自动生成。

````
//初始认证Skope API
$service = new SkopeSearch($access_key,$secret_key);
````

##3. 建立图片索引库

###3.1 上传图片

Skope搜索引擎是一款具有独立性和专有性的可定制化解决方案。每一位用户都可以建立自己的图片索引库来开发应用，其图片数据的一切权限与责任归用户所有。

用户可选择两种建立图片索引库的方式：

（1）通过API上传：上传时，用户需确保每一张图片都有独立的图片ID (```im_name```)和图片链接 (```im_url```)；图片要能从图片链接中公开下载。

（2）通过后台由Skope工程师上传：如果数据量极大但数据格式严谨规范，可由Skope工程师直接从后台快速录入数据。（可定制）

选择API上传时，使用```insert```指令，上传图片代码示范：

````
// 待上传的图片
$images = array();
// 每张图片的im_name和能公开下载的图片链接im_url是必有项且需具有唯一性。
$images[] = array('im_name'=>’00001’,’im_url'=>'http://example.com/images/00001.jpg');
$images[] = array('im_name'=>’00002’,’im_url'=>'http://example.com/images/00002.jpg');
$images[] = array('im_name'=>’00003’,’im_url'=>'http://example.com/images/00003.jpg');
// 上传呼叫
$response = $service->insert($images);
````
 > 每一次 ```insert``` 可以上传最多99张图片。如果图片数据量很大，建议可以每一次上传80-90张图片以提高上传速度。


在实际应用中，大量图片通常带有很多元数据信息，比如详细的商品描述，价格，地点，类别，状态等等。Skope引擎同样具有强大的文字搜索能力－－以图像信息为基准，文字信息来深化或筛选搜索结果。例如，浏览在同一价位内的相似商品，检索带有特别关键字或描述的商标信息，查找在过去两周内被搜索最多的某种颜色，等等。

图片的元数据信息在建立之初需要一个列表，为每一项元数据命名并指出数据类别，这是初始化过程的一部分，可有Skope工程师帮助配合完成。

通常，元数据信息以及列表要在图片上传前完成，但也可由Skope工程师根据用户需求在后台帮助直接建立。

元数据添加范例：

| 元数据名称 | 元数据类别 | 
| ---- | ---- | 
| location | string |
| description | text |

````
// 带有元数据的图片
$images[] = array('im_name'=>'00001','im_url'=>'http://example.com/images/00001.jpg', 'location'=>'中国', 'description'=> 五谷丰登);
// 上传呼叫
$response = $service->insert($images);
`````
 > 注意：元数据名称是会区分大小写的。Location和location是不一样的。建议统一使用小写体。


###3.2 更新图片

图片更新与上传图片类似，同样使用```insert```或```update```指令。当新上传图片的图片ID```im_name```与图片索引库中已存在图片的```im_name```相同时，新上传的图片将会替代已存在图片以完成更新。例如：

````
$images[] = array('im_name'=>'00001','im_url'=>'http://example.com/images/99999.jpg', 'location'=>'北京', 'description'=> 帝都);
// 更新
$response = $service->insert($images);
`````

###3.3 删除图片

删除图片用```remove```，例如：

````
$response = $service->remove(array("00002","00003"));
````

> ```remove``` 同样可以一次性删除不超过99张图片。


##4. 图片搜索

###4.1 内部搜索
针对图片索引库内部的图片的搜索，可应用于电商平台上的同款或相似产品推荐，比价查询等。内部搜索可用```idsearch```指令，代码范例：

````
$service->idsearch("00001");
````

###4.2 颜色搜索
用于针对某一特定颜色的图片搜索，基于RGB系统，```colorsearch```：

````
$service->colorsearch("820bbb");
````

###4.3 上传搜索
上传图片搜索```uploadsearch```可为本地图片或已知图片链接。本地图片上传需要创建一条本地图片路径。

本地上传搜索：

````
$image = new Image($imagePath);
$response = $service->uploadsearch($image);
````

图片链接上传搜索：

````
$image = new Image('http://example.com/images/10000.jpg');
$response = $service->uploadsearch($image);
````
在这里为了简化搜索步骤和加快搜索速度，如果需要重复搜索同一张上传图片，第二次搜索可直接调用之前搜索图片的临时图片ID```im_id```. 这里的```im_id```仅限于同次上传图片的再搜索或优化搜索，是临时产生的ID，与图片索引库中的图片ID```im_name```是截然不同的。

示例：在第一次搜索返回的response中包含搜索结果以及上传搜索图片的临时图片ID```im_id```，

````
{
    "status": "OK",
    "method": "uploadsearch",
    "error": [],
    "page": 1,
    "limit": 10,
    "total": 250,
    "result": [
        .....
    ],
    "im_id": “1234567890.jpg"
}
```
那么再次搜索时就可以调用该ID来简化搜索：

````
//调用上次上传搜索图片的临时ID
$im_id = $response->im_id;

//用临时图片ID进行二次搜索 
$new_image->set_im_id($im_id);
$response = $service->uploadsearch($new_image);
````
当然，不用临时图片ID进行二次搜索也可以，就另作一次新的上传即可。


###4.4 搜索区域

在上传搜索中，用户可以选定图片中要搜索的部分以精确搜索结果。搜索区域为矩形，由矩形左上角和右下角两个坐标来确定：

```
$image = new Image(imagePath);
// 初始默认坐标位置为(0,0), 左上角坐标为(x1,y1), 右下角坐标为(x2,y2)
$box = new Box(800,1000,900,1200);
$image->set_box($box);
```

###4.5 像素调整

当上传图片文件比较大时（图片文件上传上限为10M），可以缩减像素来控制图片大小以提高网络上传速度。默认状态下，系统可以帮助用户将上传图片转化为512x512，75%压缩率JPEG格式，如果图片过大的话。

````
$resizeSettings = new ResizeSettings();
//默认压缩
$image = new Image(imagePath, $resizeSettings);
````

但用户也可根据需要自行修改压缩比，例如：

````
//900x900，80%压缩率
$image = new Image(imagePath, new ResizeSettings(900, 900, 80));
````

###4.6 结果显示

每一次搜索Skope会返回前1000张最相似图片，用户可控制结果返回数量和每一页的结果数量（每一页一次性加载过多结果图片会造成加载速度下降）。建议每次每页加载10-20个结果。用户可通过```page```来翻页查看更多结果。
````
$page = 2;
$limit = 15;
$response = $service->colorloadsearch($image, $page, $limit);
````

每次搜索Skope都会根据默认系统算法给出每一项结果的视觉匹配度（相似度），从0到1，1为完全匹配。用户可通过```score```来查看结果匹配度。

````
$score = true;
$response = $service->idsearch($image, $page, $limit, $score);
````

###4.7 自动识别

自动识别功能目前只为电商商品服务，且产品识别类别也主要在时尚穿戴及家居方面。如有其它需求，用户可以联系Skope客服探讨定制完成。

具体识别类别可见API文档 [API自动识别](http://www.owlapi.com/api.html#自动识别). PHP操作范例：

```
$detection = 'all';
$response = $service->uploadsearch($image, $page, $limit, $score, $detection);
```

一张图片中可能有多款产品，Skope会将所有检测到的物品类别统统列出，按照匹配度排序。


##5. 高级参数搜索

###5.1 元数据检索

```fl```：关于元数据检索API，详见[API元数据检索](http://www.owlapi.com/api.html#元数据检索). PHP操作范例：

````
$fl = array("location","title","im_url");
$response = $service->uploadsearch($image, $page, $limit, $fl);
````

```
// 如果要得到全部元数据信息，则将get_all_fl设置为true：
$get_all_fl = True
$fq = array();
$response = $service->uploadsearch($image, $page, $limit, $get_all_fl);
```

###5.2 结果筛选

```fq```： 关于元结果筛选API，详见[API结果筛选](http://www.owlapi.com/api.html#结果筛选). PHP操作范例：

````
$fq = array("im_cate" => "watch");
$response = $service->uploadsearch($image, $page, $limit, $fl, $fq);
````

其中，用户也可以对结果匹配度进行筛选，比如只列出匹配度大于80%的结果：

````
$score_min = 0.8;
$score_max = 1;
$response = $service->uploadsearch($image, $page, $limit, $fl, $fq, $get_all_fl, $score,  $score_min，$score_max);
````

或者是对自动识别类别的筛选，比如一张图片中被系统识别出鞋，衣，裤三件物品，但用户只需要看裤子的结果，则：

```
$detection = ‘bottom’;
$response = $service->uploadsearch($image, $page, $limit, $fl, $fq, $get_all_fl, $score, $score_max, $score_min, $detection);
```

