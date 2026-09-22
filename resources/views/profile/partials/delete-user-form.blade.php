<form method="post" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Are you sure you want to delete your account? This cannot be undone.')">
    @csrf
    @method('delete')

    <div class="adm-form-group">
        <label class="adm-label" for="delete_password">Confirm Password</label>
        <input id="delete_password" name="password" type="password" class="adm-input" placeholder="Enter your password to confirm" required>
        @error('password', 'userDeletion') <p class="adm-error">{{ $message }}</p> @enderror
    </div>

    <button type="submit" class="adm-btn adm-btn-danger">
        Delete My Account
    </button>
</form>