<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Parser;

use simple_html_dom;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionParser()
    {
        ob_start();
        $html = file_get_html('https://gkb81.ru/sovety/');
        $h3 = $html->find('div.description h3');

        $topicCounter = count($h3);

        for ($i = 0; $i < $topicCounter; $i++) {

            $parser = new Parser();

            $customer = Parser::find()
                ->where(['title' => $parser->stripTags($h3[$i])])
                ->one();

            if (is_null($customer)) {
                $html = file_get_html('https://gkb81.ru/sovety/');

                $h3 = $html->find('div.description h3');

                foreach ($h3 as $h3_item) {
                    $item_arr_h3[] = array('h3' => $parser->stripTags($h3_item));
                }

                $h3_href = $html->find('a.header');
                foreach ($h3_href as $a_href_link_item) {
                    $item_arr_href[] = array('href' => htmlspecialchars($a_href_link_item->href));
                }

                $h3_date = $html->find('div.description li.ltx-icon-date span.dt text');

                foreach ($h3_date as $h3_date_item) {
                    $item_arr_date[] = array('date' => htmlspecialchars($h3_date_item));
                }

                $item_arr = [
                    'h3' => $item_arr_h3,
                    'href' => $item_arr_href,
                    'date' => $item_arr_date
                ];

                //----------------------------------------------------------------------------------
                $image = $html->find('img.size-post-thumbnail', $i)->src;
                $imageBaseName = pathinfo($image);

                $path = \Yii::getAlias('@runtime');
                $file = $path . '/' . $imageBaseName['basename'];

                $localImagePath = '/runtime/' . $imageBaseName['basename'];

                $Headers = @get_headers($image);
                if (preg_match("|200|", $Headers[0])) {
                    $image = file_get_contents($image);
                    file_put_contents($file, $image);
                } else {
                    echo "File not Found";
                }
                //----------------------------------------------------------------------------------

                $title = implode('', $item_arr['h3'][$i]);
                $href = implode('', $item_arr['href'][$i]);
                $date = implode('', $item_arr['date'][$i]);
                $image = $localImagePath;

                // Открыть подстраницу статьи по полученной ссылке с главной странице
                $html = file_get_html($href);
                $h3 = $html->find('div.description h3');

                $text = $html->find('div.text');

                foreach ($text as $text_item) {
                    $item_arr_text[] = array('text' => $text_item);
                }

                $item_arr = [
                    'text' => $item_arr_text
                ];

                $text = implode('', $item_arr['text'][0]);

                $parser->title = $title;
                $parser->href = $href;
                $parser->text = $text;
                $parser->date = $date;
                $parser->image = $image;

                $parser->save();

                unset($item_arr_h3);
                unset($item_arr_href);
                unset($item_arr_date);
                unset($item_arr_text);

            }

            $customer = Parser::find()
                ->select('title')
                ->all();

            echo 'Распарсены статьи: ' . $customer[$i]['title'] . '<br>';
            unset($parser);
        }
        echo '<br>Картинки сохранены в ' . \Yii::getAlias('@runtime');
    }
}