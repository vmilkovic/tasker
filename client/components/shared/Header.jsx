import Image from "next/image";
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
      <figure className="flex items-center">
        <Image
          src="/images/vm-logo-black.svg"
          width={40}
          height={40}
          layout="fixed"
        />
      </figure>
      <nav className="flex justify-center space-x-6 md:space-x-2">
        <HeaderIcon active Icon={HomeIcon} />
        <HeaderIcon Icon={CollectionIcon} />
        <HeaderIcon Icon={ClipboardListIcon} />
        <HeaderIcon Icon={CheckCircleIcon} />
        <HeaderIcon Icon={ExclamationCircleIcon} />
        <HeaderIcon Icon={ChartBarIcon} />
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
