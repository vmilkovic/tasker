import Image from "next/image";
import Link from "next/link";
import { useSession, signOut } from "next-auth/client";
import HeaderIcon from "../common/presentational/HeaderIcon";
import { InboxIcon, ChevronDownIcon } from "@heroicons/react/solid";
import {
  HomeIcon,
  CollectionIcon,
  ClipboardListIcon,
  CheckCircleIcon,
  ExclamationCircleIcon,
  ChartBarIcon,
} from "@heroicons/react/outline";

import styles from "../../styles/scss/components/shared/Header.module.scss";

function Header() {
  const [session] = useSession();

  return (
    <header className="sticky top-0 z-50 flex items-center justify-between p-2 bg-white shadow lg:px-5">
      <Link href="/">
        <a className="flex items-center cursor-pointer">
          <Image
            src="/images/vm-logo-black.svg"
            width={40}
            height={40}
            layout="fixed"
          />
        </a>
      </Link>
      <nav className="flex justify-center space-x-6 md:space-x-2">
        <HeaderIcon url="/" Icon={HomeIcon} active />
        <HeaderIcon url="/workspace" Icon={CollectionIcon} />
        <HeaderIcon url="/project" Icon={ClipboardListIcon} />
        <HeaderIcon url="/task" Icon={CheckCircleIcon} />
        <HeaderIcon url="/issue" Icon={ExclamationCircleIcon} />
        <HeaderIcon url="/analyze" Icon={ChartBarIcon} />
      </nav>
      <div className="flex items-center justify-end sm:space-x-2">
        <p className="hidden font-semibold text-center whitespace-normal xl:inline-flex">
          {session.user.name}
        </p>
        {session.user.image && (
          <Image
            onClick={signOut}
            className="rounded-full cursor-pointer"
            src={session.user.image}
            width={40}
            height={40}
            layout="fixed"
          />
        )}
        <InboxIcon className="profile-icon" />
        <ChevronDownIcon className="profile-icon" />
      </div>
    </header>
  );
}

export default Header;
