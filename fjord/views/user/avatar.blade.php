<a :href="model.profile_url" target="_blank">
    <img :src="model.avatar_url" class="w-100" style="border-radius:50%;">
</a>
<div class="text-center mt-3">
    <a :href="model.profile_url" target="_blank" style="color:black;">
        <span v-if="model.service == 'github'">
            <i class="fab fa-github"></i> GitHub
        </span>
        <span v-if="model.service == 'gitlab'">
            <i class="fab fa-gitlab"></i> GitLab
        </span>
    </a>
</div>
    

