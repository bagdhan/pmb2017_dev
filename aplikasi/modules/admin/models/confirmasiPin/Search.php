<?php
/**
 * Created by
 * User: Wisard17
 * Date: 28/01/2018
 * Time: 05.37 PM
 */

namespace app\modules\admin\models\confirmasiPin;

use yii\base\Model;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * Class Search
 * @package app\modules\admin\models\confirmasiPin
 */
class Search extends Pendaftar
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[
                'noPendaftaran',
                'orang_id',
                'rencanaPembiayaan_id',
                'manajemenJalurMasuk_id',
                'paketPendaftaran_id',
                'setujuSyarat',
                'verifikasiPMB',
                'aktifdo',
                'terimaSurat',
                'waktuBuat',
                'waktuUbah',
            ], 'safe'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public $allField = [
        'noPendaftaran' => 'noPendaftaran',
        'orang_id' => 'orang_id',
        'rencanaPembiayaan_id' => 'rencanaPembiayaan_id',
        'manajemenJalurMasuk_id' => 'manajemenJalurMasuk_id',
        'paketPendaftaran_id' => 'paketPendaftaran_id',
        'setujuSyarat' => 'setujuSyarat',
        'verifikasiPMB' => 'verifikasiPMB',
        'aktifdo' => 'aktifdo',
        'terimaSurat' => 'terimaSurat',
        'waktuBuat' => 'waktuBuat',
        'waktuUbah' => 'waktuUbah',

        'name' => '',
        'tanggalDaftar' => 'waktuBuat',
        'negara' => '',
    ];

    public function ordering($params)
    {
        $ncol = isset($params['order'][0]['column']) ? $params['order'][0]['column'] : 0;
        $col = (isset($params['columns'][$ncol]) && array_key_exists($params['columns'][$ncol]['data'], $this->allField)) ?
            $this->allField[$params['columns'][$ncol]['data']] : '';
        $argg = isset($params['order'][0]['dir']) ? $params['order'][0]['dir'] : 'asc';
        $table = 'pendaftaran';
        if (isset($params['columns'][$ncol]['data']) && $params['columns'][$ncol]['data'] == 'name') {
            $col = 'nama';
            $table = 'orang';
        }

        if (isset($params['columns'][$ncol]['data']) && $params['columns'][$ncol]['data'] == 'negara') {
            $col = 'nama';
            $table = 'negara';
        }

        return $col !== '' ? "$table.$col $argg " : '';
    }


    public $allData;

    public $currentData;

    /**
     * @param Query $query
     * @return int
     */
    public function calcAllData($query)
    {
        return $this->allData == null ? $query->count() : $this->allData;
    }

    /**
     * @param Query $query
     * @param $params
     * @return Query
     */
    public function defaultFilterByUser($query, $params)
    {


        return $query;
    }

    /**
     * @param $params
     * @param bool $export
     * @param bool $join
     * @return $this|\yii\db\ActiveQuery|Query
     */
    public function searchData($params, $export = false, $join = true)
    {

        $odr = $this->ordering($params);

        $query = self::find()->joinWith(['orang' => function ($query) {
            return $query->joinWith(['negara']);
        }, 'pinVerifikasi']);
        $query->where('orang.negara_id <> 1');


        if (isset($params['tahun']))
            $query->andWhere(['like', 'pendaftaran.waktuBuat', $params['tahun']]);

        if (!$join)
            $query = self::find();

        $query = $this->defaultFilterByUser($query, $params);

        $query->orderBy($odr);

        $start = isset($params['start']) ? $params['start'] : 0;
        $lang = isset($params['length']) ? $params['length'] : 10;

        $this->allData = $this->calcAllData($query);

        $fltr = '';
        $fltr2 = '';
        $s = 0;
        if (isset($params['columns']))
            foreach ($params['columns'] as $col) {
                if (isset($params['search']) && $params['search']['value'] != '') {
                    $lst[] = $col['data'];
                    if (array_key_exists($col['data'], $this->allField) &&
                        !array_key_exists($col['data'], $lst) && $this->allField[$col['data']] != '') {
                        $fltr .= $s != 0 ? ' or ' : '';
                        $fltr .= ' `pendaftaran`.' . $this->allField[$col['data']] . " like '%" . $params['search']['value'] . "%' ";
                        $s++;
                    }
                    if ($col['data'] == 'name') {
                        $fltr .= $s != 0 ? ' or ' : '';
                        $fltr .= ' `orang`.nama ' . " like '%" . $params['search']['value'] . "%' ";
                    }
                    if ($col['data'] == 'negara') {
                        $fltr .= $s != 0 ? ' or ' : '';
                        $fltr .= ' `negara`.nama ' . " like '%" . $params['search']['value'] . "%' ";
                    }
                }
                if ($col['searchable'] == 'true' && $col['search']['value'] != '' &&
                    array_key_exists($col['data'], $this->allField) && $this->allField[$col['data']] != '') {
                    $fltr2 .= $fltr2 == '' ? '' : ' and ';
                    $fltr2 .= ' ' . $this->allField[$col['data']] . " like '%" . $col['search']['value'] . "%' ";
                    $query->andFilterWhere(['like', '`pendaftaran`.' . $this->allField[$col['data']], $col['search']['value']]);
                }

            }

        $query->andWhere($fltr);


        $this->load($params);

        if ($export) {
            return $query;
        }

        $this->currentData = $query->count();

        $query->limit($lang)->offset($start);
        return $query;
    }

    /**
     * @param $params
     * @return mixed
     */
    public function searchDataTable($params)
    {
        $data = $this->searchData($params);
        return Json::encode([
            "draw" => isset ($params['draw']) ? intval($params['draw']) : 0,
            "recordsTotal" => intval($this->allData),
            "recordsFiltered" => intval($this->currentData),
            "data" => $this->renderData($data, $params),
        ]);
    }

    /**
     * @param $query Query
     * @param $params
     * @return array
     */
    public function renderData($query, $params)
    {
        $out = [];

        /** @var self $model */
        foreach ($query->all() as $model) {

            $out[] = array_merge(ArrayHelper::toArray($model), [
                'action' => $model->action,
                'name' => $model->name,
                'tanggalDaftar' => $model->tanggalDaftar,
                'negara' => $model->negara,
                'email' => $model->email,
            ]);

        }
        return $out;
    }
}