<?php

namespace App\Http\Controllers;

use App\Design;
use \Illuminate\Http\Request;
use Redirect;
use Response;

class IconController extends BaseController {

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \ErrorException
     */
	public function postUpload (Request $request)
	{
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            if ($file->isValid()) {
                $size = $file->getSize();
                if ($size > 10485760 /* 1024 * 1024 * 10 */) {
                    return $this->failed('请压缩文件至 10M 以下后重试.');
                }

                $id = str_random(8);
                $ext = $file->getClientOriginalExtension();

                /** @var Design $design */
                $design = new Design;
                $design->id = $id;
                $design->folder = date('Ym');
                $design->ext = $ext;
                $design->original_name = $file->getClientOriginalName();
                $design->mime_type = $file->getMimeType();
                $design->user_agent = $request->server('HTTP_USER_AGENT');
                $design->ip = $request->getClientIp();

                $files_folder = public_path('files');
                $folder = $files_folder . '/' . $design->folder . '/' . $id;
                if (!file_exists($folder) && !@mkdir($folder, 0777, TRUE) && !is_dir($folder)) {
                    throw new \ErrorException('Failed to create folder');
                }

                $file->move($folder, 'origin.' . $ext);

                $design->save();
                $design->getService()->prepare();

                return $this->success($id);
            }
        }
        return $this->failed('文件无效！');
	}

    public function postGenerate(Request $request)
    {
        $platforms = $request->get('platforms');

        /** @var Design $design */
        $design = Design::find($request->get('id'));
        $design->platform = implode(',', $platforms);
        $design->sizes = json_encode($request->get('sizes'));
        $design->bg_color = $request->get('bgColor');
        $design->ios_level = $request->get('iosLevel', '7+');
        $design->android_folder = $request->get('androidFolder');
        $design->android_name = $request->get('androidName', 'ic_launcher');
        $design->radius = (float)$request->get('radius');
        $design->save();

        try {
            $design->getService()->generateIcons();
        } catch (\ImagickException $e) {
            return $this->failed($e->getMessage());
        }

        return $this->success();
    }

    public function getDetail ($id)
    {
        return Redirect::to('/#/icon/' . $id);
    }

    public function getApiDetail($id)
    {
        /**
         * @var Design $design
         */
        $design = Design::findOrFail($id);
        $platforms = array();
        if ($design->platform) {
            $platforms = explode(',', $design->platform);
        }
        $data = array(
            'generated' => $design->getService()->isGenerated(),
            'design' => $design,
            'platforms' => $platforms
        );

        $sizes = $design->sizes;
        if ($sizes) {
            $data['platforms'][] = Design::CUSTOM_FOLDER;
            $data['sizes'] = $sizes;
        }
        return Response::json($data);
    }

    /**
     * @param $id
     *
     * @throws \ImagickException
     */
    public function getApiGenerate($id)
    {
        /**
         * @var $design Design
         */
        $design = Design::findOrFail($id);
        $design->getService()->generateIcons();
    }

    public function getDownload ($id, $regenerate = FALSE)
    {
        /** @var Design $design */
        $design = Design::findOrFail($id);
        if (!$design) {
            return '原设计图已过期！';
        }

        try {
            $path = $design->getService()->package($regenerate);
        } catch (\Exception $e) {
            return '打包失败！' . $e->getMessage();
        }

        if (!$path) {
            return '文件未找到！';
        }
        return Response::download($path);
    }

    public function postSubscribe(Request $request)
    {
        /*$subscription = new Subscription;
        $subscription->mail = $request->get('mail');
        $subscription->design_id = $request->get('design_id');
        $subscription->user_agent = $request->server('HTTP_USER_AGENT');
        $subscription->ip = $request->getClientIp();

        $subscription->save();

        $subscription->sendZip();*/
    }

}
