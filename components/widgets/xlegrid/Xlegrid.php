<?php
namespace app\components\widgets\xlegrid;

use yii\grid\GridView;
use yii\helpers\Html;


class Xlegrid extends GridView
{
    public $filterPosition = self::FILTER_POS_HEADER;
    public $filterView;// = '@app/views/dictionary/_search';
    public $gridTitle = '';
    public $additionalTitle = null;
 //   public $gridId;
 //   public $urlGetGridFilterData;

    public function run()
    {
        $r=1;
        // Register AssetBundle
        parent::run();
        XlegridAsset::register($this->getView());

    }

    /**
     * Renders the filter.
     * @return string the rendering result.
     */
    public function renderFilters()
    {
        if (isset($this->filterView) && isset($this->dataProvider->filterModel)){
            $filter = $this->dataProvider->filterModel;
            $filterButton = Html::a('<span class="glyphicon glyphicon-search"></span>', null, [
                /*
                'onclick' => '
                    if ($("#filterZone").is(":hidden")) {
                                   $("#filterZone").show("slow");
                                   $(this).css("color", "#daa520");
                        } else {
                                   $("#filterZone").hide("slow");
                                   $(this).css("color", "#00008b");
                     };
                ',
                */
                'title' => 'Редагувати',
                'onclick' => 'buttonFilterShow(this);',
            ]);
            $filterBody ='
        <td colspan='. count($this->columns) . '>
        <div class="row">
             <div class="col-md-6">
                    <b>' . $this->gridTitle .  '</b>'
             . ' ' . (isset($this->dataProvider->filterModel->additionalTitle) ? $this->dataProvider->filterModel->additionalTitle : '') .
            '</div>
            <div class="col-md-6" align="right">
                    ' . $filterButton . '
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" style="display: none" id="filterZone">
                    ' . $this->render($this->filterView, [
                    'filter' => $filter,
                ]) . '
            </div>
        </div>
        </td>

        ';
        } else {
            $filterBody ='
        <td colspan='. count($this->columns) . '>
        <div class="row">
             <div class="col-md-6">
                    <b>' . $this->gridTitle .  '</b>
            </div>
        </div>
        </td>

        ';

        }
        return $filterBody;
    }

    /**
     * Renders the table body.
     * @return string the rendering result.
     */
    public function renderTableBody() {
        $models = array_values($this->dataProvider->getModels());
        $keys = $this->dataProvider->getKeys();
        $rows = [];
        foreach ($models as $index => $model) {
            $key = $keys[$index];
            if ($this->beforeRow !== null) {
                $row = call_user_func($this->beforeRow, $model, $key, $index, $this);
                if (!empty($row)) {
                    $rows[] = $row;
                }
            }
            $rows[] = $this->renderTableRow($model, $key, $index);

            if ($this->afterRow !== null) {
                $row = call_user_func($this->afterRow, $model, $key, $index, $this);
                if (!empty($row)) {
                    $rows[] = $row;
                }
            }
        }
        //-- TODO new
        $cJSON = \Yii::$app->conservation
            ->setConserveGridDB($this->dataProvider->conserveName, $this->dataProvider->pagination->pageParam, $this->dataProvider->pagination->getPage());
        $cJSON = \Yii::$app->conservation
            ->setConserveGridDB($this->dataProvider->conserveName, $this->dataProvider->pagination->pageSizeParam, $this->dataProvider->pagination->getPageSize());
        //-- TODO new
        if (empty($rows) && $this->emptyText !== false) {
            $colspan = count($this->columns);

            return "<tbody>\n<tr><td colspan=\"$colspan\">" . $this->renderEmpty() . "</td></tr>\n</tbody>";
        } else {
            return "<tbody>\n" . implode("\n", $rows) . "\n</tbody>";
        }
    }

}