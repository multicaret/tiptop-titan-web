<div class="col-md-12 mt-2">
    <div id="accordion">
        <div class="card mb-2">
            <a class="text-body" data-toggle="collapse" href="#accordion-1">
                <h4 class="card-header">Contacts</h4>
            </a>
            <div id="accordion-1" class="collapse show" data-parent="#accordion">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <table class="table card-table">
                                    <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody v-for="(contactDetail,index) in contactDetails">
                                    <tr>
                                        <th scope="row">@{{index+1}}</th>
                                        <td>
                                            <input type="text" v-model="contactDetail.name"
                                                   class="form-control" name="contact-name">
                                            {{--                                                            <small>@{{}}</small>--}}
                                        </td>
                                        <td>
                                            <input type="text" v-model="contactDetail.email"
                                                   class="form-control" name="contact-email">
                                        </td>
                                        <td>
                                            <input type="text" v-model="contactDetail.phone"
                                                   class="form-control" name="contact-phone">
                                        </td>
                                        <td>
                                            <a class="btn btn-danger text-white"
                                               @click="removeItem(contactDetail.id)">
                                                <i class="fa fa-trash-alt"></i>
                                            </a></td>
                                    </tr>
                                    </tbody>
                                    <tr>
                                        <td>
                                            <a class="btn btn-primary" @click="addNewContact">
                                                <i class="fa fa-plus"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
