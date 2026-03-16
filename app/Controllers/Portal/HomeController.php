<?php

namespace App\Controllers\Portal;

use App\Controllers\BaseController;
use App\Models\FormCategoryModel;
use App\Models\FormTypeModel;
use App\Models\SystemSettingModel;

class HomeController extends BaseController
{
    public function index()
    {
        $formModel = new FormTypeModel();
        $categoryModel = new FormCategoryModel();
        $settingModel = new SystemSettingModel();
        $allForms = $formModel->getActiveWithCategory();
        $categories = $categoryModel->getActiveCategories();

        $categoryCards = [];
        foreach ($categories as $category) {
            $count = 0;
            foreach ($allForms as $form) {
                if (($form['category_slug'] ?? null) === $category['slug']) {
                    $count++;
                }
            }

            $categoryCards[] = $category + ['total_formulir' => $count];
        }

        return view('public/home/index', [
            'settings' => $settingModel->getMappedSettings(true),
            'featuredForms' => array_slice($allForms, 0, 6),
            'categories' => $categoryCards,
            'totalForms' => count($allForms),
        ]);
    }
}
