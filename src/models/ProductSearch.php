<?php

namespace luya\estore\models;

use Yii;
use luya\admin\ngrest\base\NgRestModel;
use luya\admin\ngrest\plugins\CheckboxRelationActiveQuery;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Product Search.
 *
 */
class ProductSearch extends Product
{
	public $articles;
	public $groups;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['name', 'producer_id', 'cover_image_id', 'images_list', 'teaser', 'text'], 'required'],
			[['name', 'images_list', 'teaser', 'text'], 'string'],
			[['producer_id', 'cover_image_id'], 'integer'],
			[['articles', 'groups'], 'safe'],
		];
	}

	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	public function search($params)
	{
		// create ActiveQuery
		$query = Product::find();
		// Important: lets join the query with our previously mentioned relations
		// I do not make any other configuration like aliases or whatever, feel free
		// to investigate that your self
		$query->joinWith([
			'groups',
			'articles' => function (\yii\db\ActiveQuery $query) {
			         $query->joinWith('prices');
			     }
	     ]);

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		// Important: here is how we set up the sorting
		// The key is the attribute name on our "TourSearch" instance
		$dataProvider->sort->attributes['groups'] = [
			// The tables are the ones our relation are configured to
			// in my case they are prefixed with "tbl_"
			'asc' => [Group::tableName().'.name' => SORT_ASC],
			'desc' => [Group::tableName().'.name' => SORT_DESC],
		];
		// Lets do the same with country now
		$dataProvider->sort->attributes['articles'] = [
			'asc' => [Article::tableName().'.name' => SORT_ASC],
			'desc' => [Article::tableName().'.name' => SORT_DESC],
		];
		// No search? Then return data Provider
		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}
		// We have to do some search... Lets do some magic
		$query->andFilterWhere([
			//... other searched attributes here
		])
			// Here we search the attributes of our relations using our previously configured
			// ones in "TourSearch"
			->andFilterWhere(['like', 'tbl_city.name', $this->city])
			->andFilterWhere(['like', 'tbl_country.name', $this->country]);

		return $dataProvider;
	}

}
