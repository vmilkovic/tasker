import Link from "next/link";

function SidebarRow({ Icon, url, title }) {
  return (
    <Link href={url}>
      <a>
        <li className="flex items-center p-4 space-x-2 rounded cursor-pointer hover:bg-red-500 group">
          {Icon && (
            <Icon className="w-5 h-5 text-gray-500 group-hover:text-white" />
          )}
          <p className="font-medium group-hover:text-white">{title}</p>
        </li>
      </a>
    </Link>
  );
}

export default SidebarRow;
