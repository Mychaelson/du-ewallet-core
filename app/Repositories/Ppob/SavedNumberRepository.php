<?php

namespace App\Repositories\Ppob;

use App\Models\Ppob\SavedNumbers;

class SavedNumberRepository
{
	function __construct(
		private SavedNumbers $savedNumbers
		)
	{
	}

	public function delete($where1)
	{
		$deleted = $this->savedNumbers->where($where1)->delete();

		return $deleted;
	}

	public function updateOrCreate($where, $update)
	{
		$savedNumber = $this->savedNumbers->updateOrCreate($where, $update);

		return collect([$savedNumber]);
	}

	public function paginate($where, $request)
	{
		$savedNumber = $this->savedNumbers->where($where);

    if(isset($request->code))
      $savedNumber = $savedNumber->where('code', $request->code);

    if(isset($request->category))
      $savedNumber = $savedNumber->where('category', $request->category);

    if(isset($request->q))
      $savedNumber = $savedNumber->where('name', 'LIKE', '%'.$request->q.'%');

    $savedNumber = $savedNumber->paginate(12);

		return $savedNumber;
	}

}
