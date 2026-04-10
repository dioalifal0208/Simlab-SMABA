import re

def update_file():
    path = r"c:\laragon\www\lab-smaba\resources\views\welcome.blade.php"
    with open(path, 'r', encoding='utf-8') as f:
        content = f.read()

    # 1. Colors
    content = content.replace("gray", "slate")
    content = content.replace("blue", "green") # Replace primary blue with green
    
    # exceptions (where maybe green wasn't intended or we want another colour)
    # the original file used some specific colors for icons in features:
    # indigo-50, emerald-50, orange-50, purple-50, cyan-50, etc.
    # by replacing 'blue-' with 'green-', it might have touched things we want to keep or change.

    # 2. Hero Section 60:40 format
    # change `grid grid-cols-1 lg:grid-cols-2` to `lg:grid-cols-12`
    content = content.replace('lg:grid-cols-2 gap-16 items-center', 'lg:grid-cols-12 gap-16 items-center')
    # and giving lg:col-span-7 to left, lg:col-span-5 to right
    # left div
    content = content.replace('<div class="flex flex-col gap-8"', '<div class="lg:col-span-7 flex flex-col gap-8"')
    # right div
    content = content.replace('<div class="relative" data-aos="fade-left"', '<div class="lg:col-span-5 relative" data-aos="fade-left"')
    
    # 3. Change Hero spacing and container size
    content = content.replace('max-w-7xl mx-auto px-6', 'max-w-[1200px] mx-auto px-6')
    content = content.replace('max-w-6xl mx-auto px-6', 'max-w-[1200px] mx-auto px-6')
    
    # 4. CTA buttons styling
    content = content.replace('shadow-green-600/20', 'shadow-[0_8px_20px_-8px_rgba(22,163,74,0.5)]')
    
    # 5. Rounded corners and shadows for cards
    content = content.replace('rounded-xl', 'rounded-2xl') # bigger radius, SaaS feel
    
    with open(path, 'w', encoding='utf-8') as f:
        f.write(content)

if __name__ == '__main__':
    update_file()
