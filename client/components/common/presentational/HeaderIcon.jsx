import Link from "next/link";

function HeaderIcon({ Icon, url, active }) {
  return (
    <>
      <Link href={url}>
        <a>
          <div className="flex items-center cursor-pointer sm:px-8 md:px-10 sm:h-12 md:hover:bg-gray-100 rounded-xl active:border-b-2 active:border-red-500 group">
            <Icon
              className={`h-5 mx-auto text-center text-gray-500 sm:h-6 md:h-7 group-hover:text-red-500 ${
                active && "text-red-500"
              }`}
            />
          </div>
        </a>
      </Link>
    </>
  );
}

export default HeaderIcon;
