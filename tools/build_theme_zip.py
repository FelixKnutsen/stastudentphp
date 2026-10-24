"""Build sta-theme.zip with standard zip entries (WordPress rejects the JS-built archive)."""
import os, zipfile
base = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
out = os.path.join(base, 'sta-theme.zip')
with zipfile.ZipFile(out, 'w', zipfile.ZIP_DEFLATED) as z:
    for root, dirs, files in os.walk(os.path.join(base, 'sta-theme')):
        dirs.sort()
        for f in sorted(files):
            p = os.path.join(root, f)
            z.write(p, os.path.relpath(p, base).replace(os.sep, '/'))
print('built', out, os.path.getsize(out))
