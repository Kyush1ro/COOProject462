{{-- We are hiding the delete account option for now as it's not typically allowed for students/instructors in an LMS --}}
{{-- 
<section>
    <div class="alert alert-danger">
        <h5 class="alert-heading">Delete Account</h5>
        <p>Once your account is deleted, all of its resources and data will be permanently deleted.</p>
        <button type="button" class="btn btn-danger" data-coreui-toggle="modal" data-coreui-target="#deleteAccountModal">
            Delete Account
        </button>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')
                    
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteAccountModalLabel">Are you sure?</h5>
                        <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm.</p>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label visually-hidden">Password</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                            @error('password', 'userDeletion')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
--}}
