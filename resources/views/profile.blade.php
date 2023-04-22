@extends('layouts.app')

@section('style')
    <style>

    </style>
@endsection

@section('content')
    <div class="container-fluid flex-grow-1 px-0">
        <!-- Content Header -->
        <div class="content-header card pt-3 pb-3 px-4">
            <div class="row content-header-title">
                <div class="col-lg-3 d-flex align-items-center">
                    <h4 class="fw-bold m-0">プロフィール</h4>
                </div>
            </div>
        </div>

        <!-- Content Body -->
        <div class="card">
            <div class="card-body">
                <div class="nav-align-top mb-4">
                    <ul class="nav nav-pills mb-3 nav-fill" role="tablist">
                      <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#tab1" aria-controls="tab1" aria-selected="true">一般情報</button>
                      </li>
                      <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tab2" aria-controls="tab2" aria-selected="false" tabindex="-1">保険会社別募集人コード</button>
                      </li>
                      <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tab3" aria-controls="tab3" aria-selected="false" tabindex="-1">外部連携</button>
                      </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="tab1" role="tabpanel">
                            <form method="post">
                                @csrf
                                <h6 class="mb-b fw-semibold">1. 基本情報</h6>

                                <div class="row">
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_name">名前</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3">顧客　太郎</span>
                                            <button type="button" id="btn_edit" class="btn btn-icon btn-sm btn-primary waves-effect waves-light"><i class="fas fa-pencil"></i></button>
                                        </div>
                                        <input type="text" id="profile_name" name="profile_name" class="form-control d-none editable" value="顧客　太郎">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">性別</label>
                                        <div class="w-100 edit_disable col-form-label">
                                            <span class="fw-semibold me-3">男</span>
                                        </div>
                                        <div class="col-form-label d-none editable">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_sex" id="profile_sex_1" value="1" checked>
                                                <label class="form-check-label" for="profile_sex_1">男</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_sex" id="profile_sex_2" value="2">
                                                <label class="form-check-label" for="profile_sex_2">女</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_birthday">生年月日</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3">1990-08-01</span>
                                        </div>
                                        <input type="text" id="profile_birthday" name="profile_birthday" class="form-control d-none editable flatpickr-input" value="1990-08-01">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_address">住所</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3">港区麻布十番</span>
                                        </div>
                                        <input type="text" id="profile_address" name="profile_address" class="form-control d-none editable" value="港区麻布十番">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_my_number">マイナンバー</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3">1234 2536 2382 1212</span>
                                        </div>
                                        <input type="text" id="profile_my_number" name="profile_my_number" class="form-control d-none editable" value="1234 2536 2382 1212">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_enter_date">雇用年月日</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3"></span>
                                        </div>
                                        <input type="text" id="profile_enter_date" name="profile_enter_date" class="form-control d-none editable flatpickr-input" value="">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_exit_date">退職年月日</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3"></span>
                                        </div>
                                        <input type="text" id="profile_exit_date" name="profile_exit_date" class="form-control d-none editable flatpickr-input" placeholder="">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_die_date">死亡年月日</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3"></span>
                                        </div>
                                        <input type="text" id="profile_die_date" name="profile_die_date" class="form-control d-none editable flatpickr-input" placeholder="">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_tel">電話番号</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3">090-2938-2937</span>
                                        </div>
                                        <input type="text" id="profile_tel" name="profile_tel" class="form-control d-none editable" placeholder="" value="090-2938-2937">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_email">メールアドレス</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3">taro.syain@test.com</span>
                                        </div>
                                        <input type="text" id="profile_email" name="profile_email" class="form-control d-none editable" placeholder="" value="taro.syain@test.com">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_life_insurance_code">生保協会登録コード</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3">code1312313123</span>
                                        </div>
                                        <input type="text" id="profile_life_insurance_code" name="profile_life_insurance_code" class="form-control d-none editable" placeholder="" value="code1312313123">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_damage_insurance_code">損保協会登録コード</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3">code71231231</span>
                                        </div>
                                        <input type="text" id="profile_damage_insurance_code" name="profile_damage_insurance_code" class="form-control d-none editable" placeholder="" value="code71231231">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_fix_money">固定給</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3">200,000</span>
                                        </div>
                                        <input type="number" id="profile_fix_money" name="profile_fix_money" class="form-control d-none editable" placeholder="" value="200000">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_office_flag">事業所得フラグ</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3"></span>
                                        </div>
                                        <input type="text" id="profile_office_flag" name="profile_office_flag" class="form-control d-none editable" placeholder="">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_fee_payment">手数料支払率</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3">80</span>
                                        </div>
                                        <input type="text" id="profile_fee_payment" name="profile_fee_payment" class="form-control d-none editable" placeholder="" value="80">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_office_min_money">事業所得の最低保障額</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3"></span>
                                        </div>
                                        <input type="text" id="profile_office_min_money" name="profile_office_min_money" class="form-control d-none editable" placeholder="">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_junior_standard_month_money">厚生年金保険標準報酬月額</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3"></span>
                                        </div>
                                        <input type="number" id="profile_junior_standard_month_money" name="profile_junior_standard_month_money" class="form-control d-none editable" placeholder="">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_health_standard_month_money">健康保険標準報酬月額</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3"></span>
                                        </div>
                                        <input type="number" id="profile_health_standard_month_money" name="profile_health_standard_month_money" class="form-control d-none editable" placeholder="">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_health_insurance_customer_number">健康保険被保険者番号</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3">123123</span>
                                        </div>
                                        <input type="text" id="profile_health_insurance_customer_number" name="profile_health_insurance_customer_number" class="form-control d-none editable" placeholder="" value="123123">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_employ_insurance_customer_number">雇用保険被保険者番号</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3"></span>
                                        </div>
                                        <input type="text" id="profile_employ_insurance_customer_number" name="profile_employ_insurance_customer_number" class="form-control d-none editable" placeholder="">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_child_contribution_money">子ども拠出金区分</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3"></span>
                                        </div>
                                        <input type="text" id="profile_child_contribution_money" name="profile_child_contribution_money" class="form-control d-none editable" placeholder="">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_multi_office_flag">二以上事業所区分</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3"></span>
                                        </div>
                                        <input type="text" id="profile_multi_office_flag" name="profile_multi_office_flag" class="form-control d-none editable" placeholder="">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_tax_address">納税地住所</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3"></span>
                                        </div>
                                        <input type="text" id="profile_tax_address" name="profile_tax_address" class="form-control d-none editable" placeholder="">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_base_year_money_number">基礎年金番号</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3"></span>
                                        </div>
                                        <input type="text" id="profile_base_year_money_number" name="profile_base_year_money_number" class="form-control d-none editable" placeholder="">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_junior_year_money_percent">厚生年金の掛け目</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3"></span>
                                        </div>
                                        <input type="text" id="profile_junior_year_money_percent" name="profile_junior_year_money_percent" class="form-control d-none editable" placeholder="">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_apply_tax_money_number">適格課税事業者登録番号</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3"></span>
                                        </div>
                                        <input type="text" id="profile_apply_tax_money_number" name="profile_apply_tax_money_number" class="form-control d-none editable" placeholder="">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_company_insurance_flag">社会保険加入有無区分</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3"></span>
                                        </div>
                                        <input type="text" id="profile_company_insurance_flag" name="profile_company_insurance_flag" class="form-control d-none editable" placeholder="">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_employ_entrance_flag">雇用加入有無区分</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3"></span>
                                        </div>
                                        <input type="text" id="profile_employ_entrance_flag" name="profile_employ_entrance_flag" class="form-control d-none editable" placeholder="">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_multi_office_type">二以上事業所・主従区分</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3"></span>
                                        </div>
                                        <input type="text" id="profile_multi_office_type" name="profile_multi_office_type" class="form-control d-none editable" placeholder="">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_health_insurance_percent">健康保険の掛け目</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3"></span>
                                        </div>
                                        <input type="text" id="profile_health_insurance_percent" name="profile_health_insurance_percent" class="form-control d-none editable" placeholder="">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_line_token">LINEアクセストークン</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3"></span>
                                        </div>
                                        <input type="text" id="profile_line_token" name="profile_line_token" class="form-control d-none editable" placeholder="">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_company_file">入社書類</label>
                                        <div class="w-100 edit_disable">
                                            <a href="#" target="_blank"><span class="fw-semibold me-3">入社書類</span></a>
                                        </div>
                                        <input class="form-control d-none editable" type="file" id="profile_company_file" name="profile_company_file">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_license_file">免許証</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3"></span>
                                        </div>
                                        <input class="form-control d-none editable" type="file" id="profile_license_file" name="profile_license_file">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_car_insurance_file1">自動車保険証書1</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3"></span>
                                        </div>
                                        <input class="form-control d-none editable" type="file" id="profile_car_insurance_file1" name="profile_car_insurance_file1">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_car_file1">車検証1</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3"></span>
                                        </div>
                                        <input class="form-control d-none editable" type="file" id="profile_car_file1" name="profile_car_file1">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_car_insurance_file2">自動車保険証書2</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3"></span>
                                        </div>
                                        <input class="form-control d-none editable" type="file" id="profile_car_insurance_file2" name="profile_car_insurance_file2">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_car_file2">車検証2</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3"></span>
                                        </div>
                                        <input class="form-control d-none editable" type="file" id="profile_car_file2" name="profile_car_file2">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_car_insurance_file3">自動車保険証書3</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3"></span>
                                        </div>
                                        <input class="form-control d-none editable" type="file" id="profile_car_insurance_file3" name="profile_car_insurance_file3">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_car_file3">車検証3</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3"></span>
                                        </div>
                                        <input class="form-control d-none editable" type="file" id="profile_car_file3" name="profile_car_file3">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_other_file">その他の書類</label>
                                        <div class="w-100 edit_disable">
                                            <a href="#" target="_blank"><span class="fw-semibold me-3">その他の書類</span></a>
                                        </div>
                                        <input class="form-control d-none editable" type="file" id="profile_other_file" name="profile_other_file">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_call_name">通称名</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3"></span>
                                        </div>
                                        <input type="text" id="profile_call_name" name="profile_call_name" class="form-control d-none editable" placeholder="">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_salary_bank">給与振込口座（銀行）</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3"></span>
                                        </div>
                                        <input type="text" id="profile_salary_bank" name="profile_salary_bank" class="form-control d-none editable" placeholder="">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_salary_bank_company">給与振込口座（支店番号）</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3"></span>
                                        </div>
                                        <input type="text" id="profile_salary_bank_company" name="profile_salary_bank_company" class="form-control d-none editable" placeholder="">
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_salary_bank_number">給与振込口座（口座番号）</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3"></span>
                                        </div>
                                        <input type="text" id="profile_salary_bank_number" name="profile_salary_bank_number" class="form-control d-none editable" placeholder="">
                                    </div>
                                </div>
                
                                <div class="row">
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">一般課程資格</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3">なし</span>
                                        </div>
                                        <div class="d-none editable">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_general" id="profile_certify_general_1" value="1">
                                                <label class="form-check-label" for="profile_certify_general_1">あり</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_general" id="profile_certify_general_2" value="2" checked>
                                                <label class="form-check-label" for="profile_certify_general_2">なし</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">専門課程資格</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3">あり</span>
                                        </div>
                                        <div class="d-none editable">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_major" id="profile_certify_major_1" value="1" checked>
                                                <label class="form-check-label" for="profile_certify_major_1">あり</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_major" id="profile_certify_major_2" value="2">
                                                <label class="form-check-label" for="profile_certify_major_2">なし</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">変額保険資格</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3">なし</span>
                                        </div>
                                        <div class="d-none editable">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_c_money_insurance" id="profile_certify_c_money_insurance_1" value="1">
                                                <label class="form-check-label" for="profile_certify_c_money_insurance_1">あり</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_c_money_insurance" id="profile_certify_c_money_insurance_2" value="2" checked>
                                                <label class="form-check-label" for="profile_certify_c_money_insurance_2">なし</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">外貨保険資格</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3">なし</span>
                                        </div>
                                        <div class="d-none editable">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_f_money_insurance" id="profile_certify_f_money_insurance_1" value="1">
                                                <label class="form-check-label" for="profile_certify_f_money_insurance_1">あり</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_f_money_insurance" id="profile_certify_f_money_insurance_2" value="2" checked>
                                                <label class="form-check-label" for="profile_certify_f_money_insurance_2">なし</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">応用保険資格</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3">なし</span>
                                        </div>
                                        <div class="d-none editable">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_apply_insurance" id="profile_certify_apply_insurance_1" value="1">
                                                <label class="form-check-label" for="profile_certify_apply_insurance_1">あり</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_apply_insurance" id="profile_certify_apply_insurance_2" value="2" checked>
                                                <label class="form-check-label" for="profile_certify_apply_insurance_2">なし</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">生命保険大学資格</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3">なし</span>
                                        </div>
                                        <div class="d-none editable">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_life_insurance_university" id="profile_certify_life_insurance_university_1" value="1">
                                                <label class="form-check-label" for="profile_certify_life_insurance_university_1">あり</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_life_insurance_university" id="profile_certify_life_insurance_university_2" value="2" checked>
                                                <label class="form-check-label" for="profile_certify_life_insurance_university_2">なし</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">損保基礎資格</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3">なし</span>
                                        </div>
                                        <div class="d-none editable">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_damage_insurance" id="profile_certify_damage_insurance_1" value="1">
                                                <label class="form-check-label" for="profile_certify_damage_insurance_1">あり</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_damage_insurance" id="profile_certify_damage_insurance_2" value="2" checked>
                                                <label class="form-check-label" for="profile_certify_damage_insurance_2">なし</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">損保自動車保険資格</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3">なし</span>
                                        </div>
                                        <div class="d-none editable">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_car_insurance" id="profile_certify_car_insurance_1" value="1">
                                                <label class="form-check-label" for="profile_certify_car_insurance_1">あり</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_car_insurance" id="profile_certify_car_insurance_2" value="2" checked>
                                                <label class="form-check-label" for="profile_certify_car_insurance_2">なし</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">損保火災保険資格</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3">なし</span>
                                        </div>
                                        <div class="d-none editable">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_fire_insurance" id="profile_certify_fire_insurance_1" value="1">
                                                <label class="form-check-label" for="profile_certify_fire_insurance_1">あり</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_fire_insurance" id="profile_certify_fire_insurance_2" value="2" checked>
                                                <label class="form-check-label" for="profile_certify_fire_insurance_2">なし</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">損保傷害保険資格</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3">なし</span>
                                        </div>
                                        <div class="d-none editable">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_accident_insurance" id="profile_certify_accident_insurance_1" value="1">
                                                <label class="form-check-label" for="profile_certify_accident_insurance_1">あり</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_accident_insurance" id="profile_certify_accident_insurance_2" value="2" checked>
                                                <label class="form-check-label" for="profile_certify_accident_insurance_2">なし</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">損保専門コース資格</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3">なし</span>
                                        </div>
                                        <div class="d-none editable">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_damage_course" id="profile_certify_damage_course_1" value="1">
                                                <label class="form-check-label" for="profile_certify_damage_course_1">あり</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_damage_course" id="profile_certify_damage_course_2" value="2" checked>
                                                <label class="form-check-label" for="profile_certify_damage_course_2">なし</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">損保コンサルティングコース資格</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3">なし</span>
                                        </div>
                                        <div class="d-none editable">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_damage_consulting_course" id="profile_certify_damage_consulting_course_1" value="1">
                                                <label class="form-check-label" for="profile_certify_damage_consulting_course_1">あり</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_damage_consulting_course" id="profile_certify_damage_consulting_course_2" value="2" checked>
                                                <label class="form-check-label" for="profile_certify_damage_consulting_course_2">なし</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">FP 3級</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3">なし</span>
                                        </div>
                                        <div class="d-none editable">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_fp3" id="profile_certify_fp3_1" value="1">
                                                <label class="form-check-label" for="profile_certify_fp3_1">あり</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_fp3" id="profile_certify_fp3_2" value="2" checked>
                                                <label class="form-check-label" for="profile_certify_fp3_2">なし</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">FP 2級</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3">なし</span>
                                        </div>
                                        <div class="d-none editable">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_fp2" id="profile_certify_fp2_1" value="1">
                                                <label class="form-check-label" for="profile_certify_fp2_1">あり</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_fp2" id="profile_certify_fp2_2" value="2" checked>
                                                <label class="form-check-label" for="profile_certify_fp2_2">なし</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">FP 1級</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3">なし</span>
                                        </div>
                                        <div class="d-none editable">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_fp1" id="profile_certify_fp1_1" value="1">
                                                <label class="form-check-label" for="profile_certify_fp1_1">あり</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_fp1" id="profile_certify_fp1_2" value="2" checked>
                                                <label class="form-check-label" for="profile_certify_fp1_2">なし</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">AFP</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3">なし</span>
                                        </div>
                                        <div class="d-none editable">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_afp" id="profile_certify_afp_1" value="1">
                                                <label class="form-check-label" for="profile_certify_afp_1">あり</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_afp" id="profile_certify_afp_2" value="2" checked>
                                                <label class="form-check-label" for="profile_certify_afp_2">なし</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">CFP</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3">なし</span>
                                        </div>
                                        <div class="d-none editable">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_cfp" id="profile_certify_cfp_1" value="1">
                                                <label class="form-check-label" for="profile_certify_cfp_1">あり</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_cfp" id="profile_certify_cfp_2" value="2" checked>
                                                <label class="form-check-label" for="profile_certify_cfp_2">なし</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">MDRT</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3">なし</span>
                                        </div>
                                        <div class="d-none editable">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_mdrt" id="profile_certify_mdrt_1" value="1">
                                                <label class="form-check-label" for="profile_certify_mdrt_1">あり</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_mdrt" id="profile_certify_mdrt_2" value="2" checked>
                                                <label class="form-check-label" for="profile_certify_mdrt_2">なし</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">COT</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3">なし</span>
                                        </div>
                                        <div class="d-none editable">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_cot" id="profile_certify_cot_1" value="1">
                                                <label class="form-check-label" for="profile_certify_cot_1">あり</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_cot" id="profile_certify_cot_2" value="2" checked>
                                                <label class="form-check-label" for="profile_certify_cot_2">なし</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label">TOT</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3">なし</span>
                                        </div>
                                        <div class="d-none editable">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_tot" id="profile_certify_tot_1" value="1">
                                                <label class="form-check-label" for="profile_certify_tot_1">あり</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="profile_certify_tot" id="profile_certify_tot_2" value="2" checked>
                                                <label class="form-check-label" for="profile_certify_tot_2">なし</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 mb-3">
                                        <label class="form-label" for="profile_damage_insurance_period">損保基礎有効期限</label>
                                        <div class="w-100 edit_disable">
                                            <span class="fw-semibold me-3">2026-07-25</span>
                                        </div>
                                        <input type="text" id="profile_damage_insurance_period" name="profile_damage_insurance_period" class="form-control d-none editable flatpickr-input" placeholder="" value="2026-07-25">
                                    </div>
                                </div>

                                <hr class="my-4 mx-n4">
                                <h6 class="mb-3 fw-semibold">2. PC情報 </h6>
                                <div class="system-profile-pc-repeater">
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <button type="button" class="btn btn-primary waves-effect waves-light d-none editable" data-repeater-create><i class="fas fa-plus me-1"></i> PC追加</button>
                                        </div>
                                    </div>
                                    <div class="table-responsive text-nowrap">
                                        <table class="table table-bordered text-center">
                                            <thead>
                                                <tr>
                                                    <th class="min-w-px-150">メーカー名</th>
                                                    <th class="min-w-px-150">タイプ</th>
                                                    <th class="min-w-px-150">シリアルナンバー</th>
                                                    <th class="min-w-px-150">OSバージョン</th>
                                                    <th class="min-w-px-50">ファイル共有ソフト有無</th>
                                                    <th class="min-w-px-50">セキュリティソフト有無</th>
                                                    <th class="min-w-px-200">データ削除日</th>
                                                    <th class="min-w-px-40"></th>
                                                </tr>
                                            </thead>
                                            <tbody data-repeater-list="item-list">
                                                {{--  item template  --}}
                                                <tr class="d-none" data-repeater-item>
                                                    <td><input type="text" name="profile_pc_maker_0" name="profile_pc_maker[]" class="form-control"></td>
                                                    <td><input type="text" name="profile_pc_type_0" name="profile_pc_type[]" class="form-control"></td>
                                                    <td><input type="text" name="profile_pc_serial_0" name="profile_pc_serial[]" class="form-control"></td>
                                                    <td><input type="text" name="profile_pc_os_version_0" name="profile_pc_os_version[]" class="form-control"></td>
                                                    <td>
                                                        <select id="profile_pc_file_share_0" name="profile_pc_file_share[]" class="form-control">
                                                            <option value="あり">あり</option>
                                                            <option value="なし">なし</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select id="profile_pc_security_0" name="profile_pc_security[]" class="form-control">
                                                            <option value="あり">あり</option>
                                                            <option value="なし">なし</option>
                                                        </select>
                                                    </td>
                                                    <td><input type="text" name="profile_pc_delete_date_0" name="profile_pc_delete_date[]" class="form-control flatpickr-input"></td>
                                                    <td>
                                                        <button type="button" class="btn btn-icon btn-outline-dribbble" data-repeater-delete><i class="fas fa-trash"></i></button>
                                                    </td>
                                                </tr>

                                                {{--  exist data showing  --}}
                                                <tr data-repeater-item>
                                                    <td>
                                                        <span class="edit_disable"></span>
                                                        <input type="text" name="profile_pc_maker_1" name="profile_pc_maker[]" class="form-control d-none editable">
                                                    </td>
                                                    <td>
                                                        <span class="edit_disable"></span>
                                                        <input type="text" name="profile_pc_type_1" name="profile_pc_type[]" class="form-control d-none editable">
                                                    </td>
                                                    <td>
                                                        <span class="edit_disable"></span>
                                                        <input type="text" name="profile_pc_serial_1" name="profile_pc_serial[]" class="form-control d-none editable">
                                                    </td>
                                                    <td>
                                                        <span class="edit_disable"></span>
                                                        <input type="text" name="profile_pc_os_version_1" name="profile_pc_os_version[]" class="form-control d-none editable">
                                                    </td>
                                                    <td>
                                                        <span class="edit_disable">なし</span>
                                                        <select id="profile_pc_file_share_1" name="profile_pc_file_share[]" class="form-control d-none editable">
                                                            <option value="あり">あり</option>
                                                            <option value="なし" selected>なし</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <span class="edit_disable">あり</span>
                                                        <select id="profile_pc_security_1" name="profile_pc_security[]" class="form-control d-none editable">
                                                            <option value="あり">あり</option>
                                                            <option value="なし">なし</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <span class="edit_disable"></span>
                                                        <input type="text" name="profile_pc_delete_date_1" name="profile_pc_delete_date[]" class="form-control d-none editable flatpickr-input">
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-icon btn-outline-dribbble d-none editable" data-repeater-delete><i class="fas fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <hr class="my-4 mx-n4">
                                <h6 class="mb-3 fw-semibold">3. 扶養家族情報</h6>
                                <div class="system-profile-dependency-repeater">
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <button type="button" class="btn btn-primary waves-effect waves-light d-none editable" data-repeater-create><i class="fas fa-plus me-1"></i> 家族追加</button>
                                        </div>
                                    </div>
                                    <div class="table-responsive text-nowrap">
                                        <table class="table table-bordered text-center">
                                            <thead>
                                                <tr>
                                                    <th class="min-w-px-150">関係</th>
                                                    <th class="min-w-px-150">名前</th>
                                                    <th class="min-w-px-100">性別</th>
                                                    <th class="min-w-px-200">生年月日</th>
                                                    <th class="min-w-px-250">住所</th>
                                                    <th class="min-w-px-150">マイナンバー</th>
                                                    <th class="min-w-px-100">扶養/被扶養</th>
                                                    <th class="min-w-px-40"></th>
                                                </tr>
                                            </thead>
                                            <tbody data-repeater-list="item-list">
                                                {{--  item template  --}}
                                                <tr class="d-none" data-repeater-item>
                                                    <td>
                                                        <select id="profile_reference_0" name="profile_reference[]" class="form-control">
                                                            <option value="配偶者">配偶者</option>
                                                        </select>
                                                    </td>
                                                    <td><input type="text" name="profile_reference_name_0" name="profile_reference_name[]" class="form-control"></td>
                                                    <td>
                                                        <select id="profile_reference_sex_0" name="profile_reference_sex[]" class="form-control">
                                                            <option value="男">男</option>
                                                            <option value="女">女</option>
                                                        </select>
                                                    </td>
                                                    <td><input type="text" name="profile_reference_birthday_0" name="profile_reference_birthday[]" class="form-control flatpickr-input"></td>
                                                    <td><input type="text" name="profile_reference_address_0" name="profile_reference_address[]" class="form-control"></td>
                                                    <td><input type="text" name="profile_reference_my_number_0" name="profile_reference_my_number[]" class="form-control"></td>
                                                    <td>
                                                        <select id="profile_reference_type_0" name="profile_reference_type[]" class="form-control">
                                                            <option value=""></option>
                                                            <option value="扶養">扶養</option>
                                                            <option value="被扶養">被扶養</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-icon btn-outline-dribbble" data-repeater-delete><i class="fas fa-trash"></i></button>
                                                    </td>
                                                </tr>

                                                {{--  exist data showing  --}}
                                                <tr data-repeater-item>
                                                    <td>
                                                        <span class="edit_disable">配偶者</span>
                                                        <select id="profile_reference_1" name="profile_reference[]" class="form-control d-none editable">
                                                            <option value="配偶者">配偶者</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <span class="edit_disable">社員　花子</span>
                                                        <input type="text" name="profile_reference_name_1" name="profile_reference_name[]" class="form-control d-none editable" value="社員　花子">
                                                    </td>
                                                    <td>
                                                        <span class="edit_disable">男</span>
                                                        <select id="profile_reference_sex_1" name="profile_reference_sex[]" class="form-control d-none editable">
                                                            <option value="男">男</option>
                                                            <option value="女">女</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <span class="edit_disable"></span>
                                                        <input type="text" name="profile_reference_birthday_1" name="profile_reference_birthday[]" class="form-control d-none editable flatpickr-input">
                                                    </td>
                                                    <td>
                                                        <span class="edit_disable"></span>
                                                        <input type="text" name="profile_reference_address_1" name="profile_reference_address[]" class="form-control d-none editable">
                                                    </td>
                                                    <td>
                                                        <span class="edit_disable"></span>
                                                        <input type="text" name="profile_reference_my_number_1" name="profile_reference_my_number[]" class="form-control d-none editable">
                                                    </td>
                                                    <td>
                                                        <span class="edit_disable"></span>
                                                        <select id="profile_reference_type_1" name="profile_reference_type[]" class="form-control d-none editable">
                                                            <option value=""></option>
                                                            <option value="扶養">扶養</option>
                                                            <option value="被扶養">被扶養</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-icon btn-outline-dribbble d-none editable" data-repeater-delete><i class="fas fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <hr class="my-4 mx-n4">

                                <div class="row">
                                    <div class="col-12 text-center editable d-none">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light"><i class="fas fa-save me-1"></i>保存する</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="tab2" role="tabpanel">
                            <div class="table-responsive text-nowrap">
                                <table class="table table-bordered table-striped text-center">
                                    <thead class="table-light">
                                        <tr>
                                            <th>保険会社</th>
                                            <th>担当者コード</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>FWD生命保険</td>
                                            <td>20EJAAC006660</td>
                                        </tr>
                                        <tr>
                                            <td>FWD生命保険</td>
                                            <td>20EJAAC006660</td>
                                        </tr>
                                        <tr>
                                            <td>FWD生命保険</td>
                                            <td>20EJAAC006660</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab3" role="tabpanel">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="d-flex align-items-start mb-2">
                                        <div class="avatar me-2">
                                            <i class="fas fa-calendar"></i>
                                        </div>
                                        <div class="me-2 ms-1">
                                          <h5 class="mb-0">Google Calendar</h5>
                                          <div class="client-info">
                                            <strong>状態: </strong><span class="text-muted">接続済</span>
                                          </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-primary waves-effect waves-light w-px-150">接続</button>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="d-flex align-items-start mb-2">
                                        <div class="avatar me-2">
                                            <i class="fas fa-message"></i>
                                        </div>
                                        <div class="me-2 ms-1">
                                          <h5 class="mb-0">Messaging API</h5>
                                          <div class="client-info">
                                            <strong>状態: </strong><span class="text-muted">未接続</span>
                                          </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-primary waves-effect waves-light w-px-150">接続</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('script')
<script src="{{asset('ui/assets/vendor/libs/jquery-repeater/jquery-repeater.js')}}"></script>

<script>
    $(document).ready(function(){
        'use strict';

        $('.system-profile-pc-repeater, .system-profile-dependency-repeater').repeater({
            show: function () {
                $(this).slideDown().removeClass("d-none");
            },
            hide: function (deleteElement) {
                console.log(deleteElement);
                if(confirm('本当に削除しますか？')) {
                    $(this).slideUp(deleteElement);
                }
            }
        });
    });

    $(function () {
        $("#btn_edit").on("click", function(e){
            $(".editable").removeClass("d-none");
            $(".edit_disable").addClass("d-none");
        });
    });
</script>
@endsection

