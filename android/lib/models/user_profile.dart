class UserProfile {
  String firstName;
  String lastName;
  String email;
  String username;
  String bio;
  String websiteLink;
  String githubLink;
  String socialLink;
  String cp;

  UserProfile({
    required this.firstName,
    required this.lastName,
    required this.email,
    required this.username,
    this.bio = 'Parsa Journals Member',
    this.websiteLink = '',
    this.githubLink = '',
    this.socialLink = '',
    this.cp = '0',
  });

  String get fullName => '$firstName $lastName'.trim();

  bool get isOwner => email.trim().toLowerCase() == 'parsabe99@gmail.com';

  String get initials {
    final fn = firstName.trim();
    final ln = lastName.trim();
    if (fn.isNotEmpty && ln.isNotEmpty) {
      return '${fn[0]}${ln[0]}'.toUpperCase();
    } else if (fn.isNotEmpty) {
      return fn.substring(0, fn.length >= 2 ? 2 : 1).toUpperCase();
    } else if (email.isNotEmpty) {
      return email[0].toUpperCase();
    }
    return 'U';
  }

  factory UserProfile.fromFullnameAndEmail(String name, String email) {
    final cleanEmail = email.trim().toLowerCase();
    final isOwnerAccount = cleanEmail == 'parsabe99@gmail.com';

    if (isOwnerAccount) {
      return UserProfile(
        firstName: 'Parsa',
        lastName: 'Besharat',
        email: 'parsabe99@gmail.com',
        username: 'parsabe',
        bio: 'Lead AI Researcher & Platform Founder',
        websiteLink: 'https://parsabe.com',
        githubLink: 'https://github.com/parsabe',
        socialLink: 'https://x.com/parsabe',
        cp: '∞',
      );
    }

    final cleanName = name.trim();
    final parts = cleanName.split(' ');
    String first = parts.isNotEmpty ? parts.first : 'User';
    String last = parts.length > 1 ? parts.sublist(1).join(' ') : '';
    String uname = cleanEmail.contains('@') ? cleanEmail.split('@').first : cleanEmail;
    if (uname.isEmpty) uname = 'user_${DateTime.now().millisecondsSinceEpoch % 1000}';

    return UserProfile(
      firstName: first,
      lastName: last,
      email: email,
      username: uname,
      bio: 'Parsa Journals Member',
      websiteLink: '',
      githubLink: '',
      socialLink: '',
      cp: '0',
    );
  }

  factory UserProfile.fromJson(Map<String, dynamic> json) {
    final email = json['email'] ?? '';
    final isOwnerAccount = email.toString().trim().toLowerCase() == 'parsabe99@gmail.com';

    return UserProfile(
      firstName: json['first_name'] ?? json['name'] ?? 'User',
      lastName: json['last_name'] ?? '',
      email: email,
      username: json['username'] ?? (email.toString().contains('@') ? email.toString().split('@').first : 'user'),
      bio: json['bio'] ?? 'Parsa Journals Member',
      websiteLink: json['website_link'] ?? '',
      githubLink: json['github_link'] ?? '',
      socialLink: json['social_link'] ?? '',
      cp: isOwnerAccount ? '∞' : (json['cp']?.toString() ?? '0'),
    );
  }

  Map<String, dynamic> toJson() => {
        'first_name': firstName,
        'last_name': lastName,
        'email': email,
        'username': username,
        'bio': bio,
        'website_link': websiteLink,
        'github_link': githubLink,
        'social_link': socialLink,
        'cp': isOwner ? '∞' : cp,
      };
}
