<div class="modal fade account-modal" id="register-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <a class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class="lil-close"></i></span>
            </a>
            <div class="modal-body">
                {!! Form::open(['url' => '/register']) !!}
                <h3 class="modal-title text-center">حساب جديد</h3>
                <p class="text-center">أنضم الان الى منصة كاتب وكتاب، قبل الجميع</p>
                @component('admin.components.form-group', ['name' => 'name', 'type' => 'text'])
                    @slot('label', 'Full Name')
                    @slot('attributes', ['required', 'placeholder' => 'الاسم الظاهر'])
                @endcomponent
                @component('admin.components.form-group', ['name' => 'email', 'type' => 'email'])
                    @slot('label', 'Email')
                    @slot('attributes', ['required', 'placeholder' => 'name@mail.com'])
                @endcomponent
                @component('admin.components.form-group', ['name' => 'username', 'type' => 'text'])
                    @slot('label', 'Username')
                    @slot('attributes', ['required', 'placeholder' => '@username'])
                @endcomponent
                @component('admin.components.form-group', ['name' => 'password', 'type' => 'password'])
                    @slot('label', 'Password')
                    @slot('attributes', ['required', 'placeholder' => 'أختر كلمة مرور قوية'])
                @endcomponent
                <div class="form-group mb-0">
                    <label class="control-label form-control-label">@lang('strings.gender')</label>
                    @if (session('errorsIn') == 'signup' && $errors->has('gender'))
                        <span class="help-block">{{ $errors->first('gender') }}</span>
                    @endif
                    <div>
                        <label class="custom-checkbox custom-radio d-inline-block mr-3">
                            {!! Form::radio('gender', 'm', null, ['class' => 'custom-control-input', 'required']) !!}
                            <span class="custom-control-indicator"><i class="icon ya-check"></i></span>
                            <span class="custom-control-description">@lang('strings.male')</span>
                        </label>
                        <label class="custom-checkbox custom-radio d-inline-block">
                            {!! Form::radio('gender', 'f', null, ['class' => 'custom-control-input', 'required']) !!}
                            <span class="custom-control-indicator"><i class="icon ya-check"></i></span>
                            <span class="custom-control-description">@lang('strings.female')</span>
                        </label>
                    </div>
                </div>

                <div class="modal-btns text-center">
                    <button class="btn btn-secondary">سجل الآن</button>
                </div>
                <p class="copyright text-center">
                    ان كان لديك حساب يمكنك تسجيل الدخول
                    <a href="#!" data-modal="#login-modal">من هنا</a>
                </p>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div><!-- /#register-modal -->

<div class="modal fade account-modal" id="login-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <a class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><i class="lil-close"></i></span>
            </a>
            <div class="modal-body">
                {!! Form::open(['url' => '/login']) !!}
                <h3 class="modal-title text-center">تسجيل الدخول</h3>
                <p class="text-center">أهلا بعودتك :)</p>
                @component('admin.components.form-group', ['name' => 'login', 'type' => 'email'])
                    @slot('label', 'Email')
                    @slot('attributes', ['required', 'placeholder' => 'المستخدم اثناء التسجيل'])
                @endcomponent
                <div class="form-group">
                    <label class="control-label">@lang('strings.password')</label>
                    <a href="#!" data-modal="#reset-modal" class="float-left reset-modal">
                        <u>هل نسيت كلمة المرور؟</u>
                    </a>
                    {{ Form::password('password', ['class' => 'form-control']) }}
                </div>

                <div class="modal-btns text-center">
                    <button class="btn btn-secondary">تسجيل الدخول</button>
                </div>
                <p class="copyright text-center">
                    ان كان لديك حساب يمكنك تسجيل الدخول
                    <a href="#!" data-modal="#register-modal"><u>من هنا</u></a>
                </p>
                {!! Form::checkbox('remember', true, true, ['checked', 'class' => 'd-none']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div><!-- /#login-modal -->

<div class="modal fade account-modal" id="reset-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                {!! Form::open(['url' => '/password/email']) !!}
                <h3 class="modal-title text-center">إستعادة كلمة المرور</h3>
                <p class="text-center">
                    سوف تصلك رسالة الى البريد الإلكتروني تحمل رابط لتعين كلمة مرور
                    جديدة
                </p>
                @component('admin.components.form-group', ['name' => 'login', 'type' => 'email'])
                    @slot('label', 'Email')
                    @slot('attributes', ['required', 'placeholder' => 'المستخدم اثناء التسجيل'])
                @endcomponent

                <div class="modal-btns text-center">
                    <button class="btn btn-secondary">أرسل معلومات الاستعادة</button>
                </div>
                <p class="copyright text-center">
                    هل تذكرت كلمة المرور
                    <a href="#!" data-modal="#login-modal"><u>تذكرتها؟</u></a>
                </p>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div><!-- /#reset-modal -->

<div class="modal fade account-modal" id="reverify-modal">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body">
                {!! Form::open(['route' => 'users.reverify']) !!}
                <h3 class="modal-title text-shadow">لم يصلك رابط التفعيل؟</h3>
                <p class="text-shadow more-width">سوف نرسل لك بريد الكتروني يحتوي على تعليمات لتفعيل الحساب</p>
                <div class="form-group">
                    @if (session('errorsIn') == 'reset' && $errors->has('email'))
                        <span class="help-block">{{ $errors->first('email') }}</span>
                    @endif
                    {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'البريد الاكتروني', 'required']) !!}
                </div>

                <a href="!#" data-modal="#login-modal"><u>تسجيل الدخول</u></a>
                <div class="modal-btns">
                    <button class="btn btn-black">إرسال</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div><!-- /#reverify-modal -->
