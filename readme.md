Its a simple lib for git. You need to have git available on host. Offers three methods (atm):

`getFileNamesChangedSince(int $commits = 1)` which will return file names
of changed files since N commits

`getLineAddedToFileSince(string $fileName, int $commits = 1)` which will return content
of lines ADDED since N commits

`public function getLinesRemovedFromFileSince(string $fileName, int $commits = 1)` which will return content
of lines REMOVED since N commits

Constructor will check if git is available and check if given path is a git repo.

