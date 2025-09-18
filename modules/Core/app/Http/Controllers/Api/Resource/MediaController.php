<?php
/**
 * Concord CRM - https://www.concordcrm.com
 *
 * @version   1.6.0
 *
 * @link      Releases - https://www.concordcrm.com/releases
 * @link      Terms Of Service - https://www.concordcrm.com/terms
 *
 * @copyright Copyright (c) 2022-2025 KONKORD DIGITAL
 */

namespace Modules\Core\Http\Controllers\Api\Resource;

use Illuminate\Http\JsonResponse;
use MediaUploader;
use Modules\Core\Contracts\Resources\Mediable;
use Modules\Core\Facades\Innoclapps;
use Modules\Core\Http\Controllers\ApiController;
use Modules\Core\Http\Requests\ResourceRequest;
use Modules\Core\Http\Resources\MediaResource;
use Plank\Mediable\Exceptions\MediaUploadException;
use Plank\Mediable\HandlesMediaUploadExceptions;

class MediaController extends ApiController
{
    use HandlesMediaUploadExceptions;

    /**
     * Upload media to resource.
     */
    public function store(ResourceRequest $request): JsonResponse
    {
        abort_unless($request->resource() instanceof Mediable, 404);

        $this->authorize('update', $record = $request->record());

        try {
            $media = MediaUploader::fromSource($request->file('file'))
                ->toDirectory($record->getMediaDirectory())
                ->setAllowedExtensions(Innoclapps::allowedUploadExtensions())
                ->upload();
        } catch (MediaUploadException $e) {
            $exception = $this->transformMediaUploadException($e);

            return $this->response(
                ['message' => $exception->getMessage()],
                $exception->getStatusCode()
            );
        }

        $record->attachMedia($media, $record->getMediaTags());

        return $this->response(new MediaResource($media), JsonResponse::HTTP_CREATED);
    }

    /**
     * Delete media from resource.
     */
    public function destroy(ResourceRequest $request): JsonResponse
    {
        abort_unless($request->resource() instanceof Mediable, 404);

        $resource = $request->record();

        $this->authorize('update', $resource);

        $media = $resource->media()->findOrFail($request->route('media'));

        abort_if($media->viaTextAttribute(), 404);

        $media->delete();

        return $this->response('', JsonResponse::HTTP_NO_CONTENT);
    }
}
